<?php

declare(strict_types=1);

namespace App\Commands;

use MilesChou\TwnicIp\TwnicIp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    protected function configure()
    {
        $this->setName('check')
            ->setDescription('Check ip')
            ->addArgument('ips', InputArgument::IS_ARRAY, 'Ip list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ips = $input->getArgument('ips');

        foreach ($ips as $ip) {
            if ((new TwnicIp())->isTaiwan($ip)) {
                $output->writeln("$ip is Taiwan");
            } else {
                $output->writeln("$ip is NOT Taiwan");
            }
        }

        return 0;
    }
}
