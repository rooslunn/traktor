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

$app = new Application('Images Processor Bot', '0.1');

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

$app->run();
