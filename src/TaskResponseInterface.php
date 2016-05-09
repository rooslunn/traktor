<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:18 PM
 */

namespace Traktor\Bot;


interface TaskResponseInterface
{
    public function isSuccess(): bool;
}