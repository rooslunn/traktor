<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 3:49 PM
 */

namespace Traktor\Bot\Exception;


class MkdirFailed extends \Exception
{
    public function __construct($path)
    {
        $message = sprintf("Can't create dir: %s", $path);
        parent::__construct($message);
    }
}