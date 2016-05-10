<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 3:13 PM
 */

if (! function_exists('config')) {
    function config(string $key, string $defaultValue = null) {
        return \Traktor\Bot\Config::getInstance()->get($key, $defaultValue);
    }
}

