# Images Processor Bot #

[![Scrutinizer Code Quality][ico-code-quality]][link-code-quality]
[![Build Status][ico-travis]][link-travis]

**Requirements: PHP 7.0, [RabbitMQ](http://www.rabbitmq.com/download.html)**

CLI script for batch image processing.

## Setup ##

Provided you have [composer](http://getcomposer.org) installed, you can run the following command:

```bash
$ composer.phar install
```

## Usage ##

Running bot without arguments output full list of supported commands:
 
```bash
$ php bot.php
```

```bash
Images Processor Bot version 0.1

Usage:
  command [options] [arguments]

Available commands:
  resize    Resize next images from the queue
  retry     Retry failed tasks
  schedule  Add filenames to resize queue
  status    Output current status in format queue: number_of_images
  upload    Upload next images to remote storage
```

### 1. Scheduler ###

Accepts a path to the directory with images and schedule them for resize, i.e. adds to resize queue.

```bash
$ php bot.php schedule ./images
```

### 2. Resizer ###

akes next count of images from resize queue and resizes them to 640x640 pixels in jpg format. 
If image is not a square shape resizer will make it square by means of adding a white background. 
If there is an error URL will be moved to failed queue.

```bash
$ php bot.php resize [-N <count>]
```

If parameter -n is omitted resize processes all images from resize queue.

### 3. Uploader ###

Uploads next count of images from upload queue to Google Drive Storage.

```bash
$ php bot.php upload [-N <count>]
```

If parameter -n is omitted resize processes all images from resize queue.

### 4. Monitoring ###

Outputs all queues with a count of URLs in each of them.

```bash
$ php bot.php status
```

```bash
Images Processor Bot
Queue      Count 
resize     4     
upload     0     
done       0     
failed     1  
```

### 5. Rescheduler ###

Moves all URLs from failed  queue back to upload  queue.

```bash
$ php bot.php retry [-N <count>]
```

Author: Rooslunn <rooslunn@gmail.com>

[ico-code-quality]: https://scrutinizer-ci.com/g/rooslunn/traktor/badges/quality-score.png?b=master
[link-code-quality]: https://scrutinizer-ci.com/g/rooslunn/traktor/?branch=master
[ico-php7-ready]: http://php7ready.timesplinter.ch/rooslunn/traktor/badge.svg
[ico-travis]: https://travis-ci.org/rooslunn/traktor.svg?branch=master
[link-travis]: https://travis-ci.org/rooslunn/traktor
