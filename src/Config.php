<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 2:44 PM
 */

namespace Traktor\Bot;


use Symfony\Component\Yaml\Yaml;
use Traktor\Bot\Exception\ConfigNotExists;

class Config
{
    use SingletonTrait;

    const CONFIG_FILE = './config.yml';

    protected $yaml;

    protected function init()
    {
        if (! file_exists(self::CONFIG_FILE)) {
            throw new ConfigNotExists(self::CONFIG_FILE);
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