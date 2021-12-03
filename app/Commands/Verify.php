<?php

declare(strict_types=1);

namespace App\Commands;

use MilesChou\TwnicIp\Database;
use MilesChou\TwnicIp\TwnicIp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Verify extends Command
{
    protected function configure()
    {
        $this->setName('verify')
            ->setDescription('Verify')
            ->addOption('--interval', '-i', InputOption::VALUE_REQUIRED, 'Check when interval', 100);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $all = Database::all();

        $output->writeln('All data count: ' . count($all));

        $target = new TwnicIp();

        if ($target->isTaiwan('0.0.0.0')) {
            $output->writeln('0.0.0.0 true, check ERROR');
        }

        if ($target->isTaiwan('255.255.255.255')) {
            $output->writeln('255.255.255.255 true, check ERROR');
        }

        foreach (Database::all() as $key => $item) {
            $startCheck = $key % $input->getOption('interval') === 0;
            if ($startCheck && $output->isVerbose()) {
                $start = microtime(true);
            }

            if (!$target->isTaiwan(long2ip($item[0]))) {
                $output->writeln("{$item[0]} false, check ERROR");
            }

            if (!$target->isTaiwan(long2ip($item[1]))) {
                $output->writeln("{$item[1]} false, check ERROR");
            }

            $endCheck = ($key + 1) % $input->getOption('interval') === 0;
            if ($endCheck && $output->isVerbose()) {
                $output->writeln(sprintf(
                    'Finish %d row, used time: %.3f s',
                    $key + 1,
                    microtime(true) - $start
                ));
            }
        }

        return 0;
    }
}
