<?php

/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 9:30 AM
 */

namespace Traktor\Bot\Adapter;


use Traktor\Bot\TaskResult;

interface CloudStorage
{
    public function upload(string $filename): TaskResult;
}