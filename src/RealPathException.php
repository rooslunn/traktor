<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 8:09 AM
 */

namespace Traktor\Bot;


class RealPathException extends \Exception
{
    public function __construct($path)
    {
        $message = sprintf("Can't resolve path: %s", $path);
        parent::__construct($message);
    }
}