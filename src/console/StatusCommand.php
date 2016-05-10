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

class StatusCommand extends Command
{
    const TABLE_FORMAT = '%-10s %-6s';

    protected function configure()
    {
        $this->setName('status')
            ->setDescription('Shows queue status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Images Processor Bot');
        $output->writeln(sprintf(self::TABLE_FORMAT, 'Queue', 'Count'));
        $info = Queue::status();
        foreach ($info as $k => $v) {
            $output->writeln(sprintf(self::TABLE_FORMAT, $k, $v));
        }
    }
}