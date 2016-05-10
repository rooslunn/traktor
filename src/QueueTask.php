<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 7:23 AM
 */

namespace Traktor\Bot;


use PhpAmqpLib\Message\AMQPMessage;

interface QueueTask
{
    public function getQueueName(): string;
    public function getNextQueue(): string;
    public function getTaskLimit(): int;
    public function execute(AMQPMessage $msg): TaskResult;
}