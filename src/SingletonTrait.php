<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 03.05.16
 * Time: 9:00 AM
 */

namespace Traktor\Bot;


trait SingletonTrait
{
    protected static $instance;

    final public static function getInstance()
    {
        return static::$instance ?? new static;
    }

    final protected function __construct()
    {
        $this->init();
    }

    protected function init()
    {

    }

    final protected function __clone()
    {
        
    }

}