<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 8:37 PM
 */

namespace Traktor\Bot\Task;


use PhpAmqpLib\Message\AMQPMessage;
use Traktor\Bot\BaseQueueTask;
use Traktor\Bot\QueueTask;
use Traktor\Bot\TaskResponse;
use Traktor\Bot\TaskResult;

class ResizeImage implements QueueTask
{
    use BaseQueueTask;
    
    const HEIGHT = 640;
    const WIDTH = 640;

    protected function resize(\Imagick $img): \Imagick
    {
        return $img;
    }

    protected function destinationFileName(string $filename): string
    {
        $folder = realpath(config('folder.images_resized'));
        return $folder . '/' . pathinfo($filename, PATHINFO_BASENAME) . '.jpg';
    }

    public function execute(AMQPMessage $msg): TaskResult
    {
        $result = new TaskResult();
        try {
            $filename = $msg->body;
            $img = new \Imagick($filename);
            $img->thumbnailImage(self::WIDTH, self::HEIGHT, true, true);
            $img->setGravity(\Imagick::GRAVITY_CENTER);
            $img->setImageBackgroundColor('white');
            $img->setImageFormat('jpg');
            $destination = $this->destinationFileName($filename);
            $img->writeImage($destination);
            unlink($filename);
            $result->code = TaskResponse::SUCCESS;
            $result->message = $destination;
        } catch (\Exception $e) {
            $result->code = TaskResponse::FAIL;
            $result->message = $e->getMessage();
        }
        return $result;
    }
}