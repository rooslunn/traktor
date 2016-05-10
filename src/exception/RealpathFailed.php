<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 3:49 PM
 */

namespace Traktor\Bot\Exception;


class RealpathFailed extends \Exception
{
    public function __construct($path)
    {
        $message = sprintf("Can't resolve path: %s", $path);
        parent::__construct($message);
    }
}