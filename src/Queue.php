<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 3:39 PM
 */

namespace Traktor\Bot;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Traktor\Bot\Exception\RealpathFailed;
use Traktor\Bot\Task\GoogleUpload;
use Traktor\Bot\Task\ResizeImage;
use Traktor\Bot\Task\RetryImages;

class Queue implements QueueInterface
{
    use SingletonTrait;

    protected $connection;

    protected function init()
    {
        $this->connection = new AMQPStreamConnection(
            config('amqp.host'), config('amqp.port'),
            config('amqp.user'), config('amqp.pswd')
        );
    }

    public function __destruct()
    {
        $this->connection->close();
    }


    protected function queuesStatus(): array
    {
        $channel = $this->connection->channel();
        $queues = [
            QueueType::RESIZE,
            QueueType::UPLOAD,
            QueueType::DONE,
            QueueType::FAILED,
        ];
        
        $result = [];
        foreach ($queues as $q) {
            list(, $jobs) = $channel->queue_declare($q, false, true, false, false);
            $result[$q] = $jobs;
        }
        $channel->close();
        
        return $result;
    }

    protected function enqueue(string $queue_name, string $message_body)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue_name, false, true, false, false);

        $msg = new AMQPMessage($message_body, ['delivery_mode' => 2]);
        $channel->basic_publish($msg, '', $queue_name);

        $channel->close();
    }

    protected function dequeue(QueueTask $task)
    {
        $queue_name = $task->getQueueName();
        $limit = $task->getTaskLimit();

        $channel = $this->connection->channel();
        list(, $jobs) = $channel->queue_declare($queue_name, false, true, false, false);

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue_name, '', false, false, false, false,
            function(AMQPMessage $msg) use ($task) {
                printf(" [+] Queue: %s, received: %s\n", $task->getQueueName(), $msg->body);
                $result = $task->execute($msg);
                if (TaskResponse::SUCCESS === $result->code) {
                    $this->enqueue($task->getNextQueue(), $result->message);
                    echo ' [+] Done', PHP_EOL;
                } else {
                    $this->enqueue(QueueType::FAILED, $msg->body);
                    echo ' [-] Failed: ', $result->message, PHP_EOL;
                }
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            }
        );

        $jobs = $limit > 0 ? min($jobs, $limit) : $jobs;
        while ($jobs-- > 0) {
            $channel->wait();
        }

        $channel->close();
    }

    protected static function dequeueWithCount(QueueTask $task)
    {
        $queue = static::getInstance();
        $queue->dequeue($task);
    }

    public static function schedule(string $path)
    {
        $realpath = realpath($path);
        if (false === $realpath) {
            throw new RealpathFailed($path);
        }

        $queue = static::getInstance();
        foreach (glob($realpath . '/*.*') as $filename) {
            $queue->enqueue(QueueType::RESIZE, $filename);
        }
    }

    public static function resize(int $limit = 0)
    {
        static::dequeueWithCount(new ResizeImage(QueueType::RESIZE, QueueType::UPLOAD, $limit));
    }

    public static function upload(int $limit = 0)
    {
        static::dequeueWithCount(new GoogleUpload(QueueType::UPLOAD, QueueType::DONE, $limit));
    }

    public static function status(): array
    {
        $queue = static::getInstance();
        return $queue->queuesStatus();
    }

    public static function retry(int $limit)
    {
        static::dequeueWithCount(new RetryImages(QueueType::FAILED, QueueType::UPLOAD, $limit));
    }
}
