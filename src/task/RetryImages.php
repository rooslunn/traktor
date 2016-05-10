<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 8:37 PM
 */

namespace Traktor\Bot\Task;


use PhpAmqpLib\Message\AMQPMessage;
use Traktor\Bot\BaseQueueTask;
use Traktor\Bot\QueueTask;
use Traktor\Bot\TaskResult;

class RetryImages implements QueueTask
{
    use BaseQueueTask;
    
    public function execute(AMQPMessage $msg): TaskResult
    {
        $result = TaskResult::initOk();
        $result->message = $msg->body;
        return $result;
    }
}