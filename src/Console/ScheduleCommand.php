<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 10.05.16
 * Time: 10:40 AM
 */

namespace Traktor\Bot\Console;

use Symfony\Component\Console\{
    Command\Command,
    Input\InputArgument,
    Input\InputOption,
    Input\InputInterface,
    Output\OutputInterface
};
use Traktor\Bot\Queue;

class ScheduleCommand extends Command
{
    protected function configure()
    {
        $this->setName('schedule')
            ->setDescription('Add filenames to resize queue')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to images folder'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        if ($path) {
            Queue::schedule($path);
        }
        $output->writeln('Ok');
    }
}