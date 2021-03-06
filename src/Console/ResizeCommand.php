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

class ResizeCommand extends Command
{
    protected function configure()
    {
        $this->setName('resize')
            ->setDescription('Resize next images from the queue')
            ->addOption(
                'count',
                'N',
                InputOption::VALUE_OPTIONAL,
                'Process first n tasks',
                0
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('count');
        Queue::resize($limit);
        $output->writeln('Ok');
    }
}