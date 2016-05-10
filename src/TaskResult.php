<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 8:50 AM
 */

namespace Traktor\Bot;


class TaskResult
{
    public $code;
    public $message;

    public static function initOk(): TaskResult
    {
        $instance = new static();
        $instance->code = TaskResponse::SUCCESS;
        $instance->message = 'Well done';
        return $instance;
    }

}