<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:08 PM
 */

namespace Traktor\Bot;


class ScheduleTaskRequest
{
    protected $imagesPath;
    
    public function __construct(string $imagesPath)
    {
        $this->imagesPath = $imagesPath;     
    }

    /**
     * @return string
     */
    public function getImagesPath()
    {
        return $this->imagesPath;
    }

}