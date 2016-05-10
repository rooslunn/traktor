<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 3:46 PM
 */

namespace Traktor\Bot\Exception;


class ConfigNotExists extends \RuntimeException
{
    public function __construct($filename, $code=0, Exception $previous=null)
    {
        $message = sprintf('Config file doesn\'t exist: %s', $filename);
        parent::__construct($message, $code, $previous);
    }
}
