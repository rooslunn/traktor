<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 12:58 PM
 */

namespace Traktor\Bot;


interface QueueInterface
{
    public function schedule(ScheduleTaskRequest $request): TaskResponseInterface;
    public function resize(NumberTaskRequest $request): TaskResponseInterface;
    public function upload(NumberTaskRequest $request): TaskResponseInterface;
    public function status();
}