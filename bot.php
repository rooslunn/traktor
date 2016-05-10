<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:56 PM
 */


require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Traktor\Bot\Console\ResizeCommand;
use Traktor\Bot\Console\ScheduleCommand;
use Traktor\Bot\Console\UploadCommand;

$app= new Application();

$app->add(new ScheduleCommand());
$app->add(new ResizeCommand());
$app->add(new UploadCommand());

$app->run();

//Queue::schedule('./images');
//Queue::resize();
//Queue::upload();
