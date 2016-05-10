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
    public static function schedule(string $path);
    public static function resize(int $limit);
    public static function upload(int $limit);
    public static function status(): array;
    public static function retry(int $limit);
}