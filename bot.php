<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 09.05.16
 * Time: 1:56 PM
 */

use Traktor\Bot\Queue;

require_once __DIR__ . '/vendor/autoload.php';

Queue::schedule('./images');
Queue::resize();
Queue::upload();
