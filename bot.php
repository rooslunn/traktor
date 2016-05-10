<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:56 PM
 */


require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Traktor\Bot\Console\{
    ResizeCommand,
    RetryCommand,
    ScheduleCommand,
    StatusCommand,
    UploadCommand
};

$app = new Application();

$commands = [
    ScheduleCommand::class,
    ResizeCommand::class,
    UploadCommand::class,
    StatusCommand::class,
    RetryCommand::class,
];

foreach ($commands as $class) {
    $app->add(new $class());
}
//$app->add(new ScheduleCommand());
//$app->add(new ResizeCommand());
//$app->add(new UploadCommand());
//$app->add(new StatusCommand());
//$app->add(new RetryCommand());

$app->run();
