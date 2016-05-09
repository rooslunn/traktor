<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 2:44 PM
 */

namespace Traktor\Bot;


use Exception;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class ConfigNotExist extends RuntimeException
{
    public function __construct($filename, $code=0, Exception $previous=null)
    {
        $message = sprintf('Config file doesn\'t exist: %s', $filename);
        parent::__construct($message, $code, $previous);
    }
}

class Config
{
    use SingletonTrait;

    const CONFIG_FILE = './config.yml';

    protected $yaml;

    protected function init()
    {
        if (! file_exists(self::CONFIG_FILE)) {
            throw new ConfigNotExist(self::CONFIG_FILE);
        }
        $this->yaml = Yaml::parse(file_get_contents(self::CONFIG_FILE));
    }

    public static function load()
    {
        return self::getInstance();
    }
    
    public function get(string $param, $default = null)
    {
        if (array_key_exists($param, $this->yaml)) {
            return $this->yaml[$param];
        }
        return $default;
    }
}