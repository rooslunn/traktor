<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 7:47 AM
 */

namespace Traktor\Bot;


trait BaseQueueTask
{
    protected $queue;
    protected $nextQueue;
    protected $limit;

    public function __construct(string $queue, string $nextQueue = '', $limit = 0)
    {
        $this->queue = $queue;
        $this->nextQueue = $nextQueue;
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queue;
    }

    /**
     * @return int
     */
    public function getTaskLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getNextQueue(): string
    {
        return $this->nextQueue;
    }

}