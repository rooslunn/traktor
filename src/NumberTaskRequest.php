<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:11 PM
 */

namespace Traktor\Bot;


class NumberTaskRequest
{
    protected $n;

    public function __construct(int $n)
    {
        $this->n = $n;
    }
}