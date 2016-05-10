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
    static public function schedule(string $path);
    static public function resize(int $limit);
}