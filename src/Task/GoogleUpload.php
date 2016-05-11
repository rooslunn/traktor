<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 9:18 AM
 */

namespace Traktor\Bot\Task;


use PhpAmqpLib\Message\AMQPMessage;
use Traktor\Bot\Adapter\GoogleDrive;
use Traktor\Bot\BaseQueueTask;
use Traktor\Bot\QueueTask;
use Traktor\Bot\TaskResult;

class GoogleUpload implements QueueTask
{
    use BaseQueueTask;

    public function execute(AMQPMessage $msg): TaskResult
    {
        $filename = $msg->body;
        $storage = new GoogleDrive();
        return $storage->upload($filename);
    }
}