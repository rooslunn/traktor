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
                echo ' [x] Received ', $msg->body, PHP_EOL;
                $result = $task->execute($msg);
                if (TaskResponse::SUCCESS === $result->code) {
                    $this->enqueue($task->getNextQueue(), $result->message);
                    echo ' [x] Done', PHP_EOL;
                } else {
                    $this->enqueue(QueueType::FAILED, $msg->body);
                    echo ' [x] Failed', PHP_EOL;
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

    static public function schedule(string $path)
    {
        $realpath = realpath($path);
        if (false === $realpath) {
            throw new RealPathException($path);
        }

        $queue = static::getInstance();
        foreach (glob($realpath . '/*.*') as $filename) {
            $queue->enqueue(QueueType::RESIZE, $filename);
        }
    }

    static public function resize(int $limit = 0)
    {
        $queue = static::getInstance();
        $queue->dequeue(new ResizeImageTask(QueueType::RESIZE, QueueType::UPLOAD, $limit));
    }
}