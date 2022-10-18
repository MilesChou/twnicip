<?php

declare(strict_types=1);

namespace App\Commands;

use Generator;
use MilesChou\TwnicIp\TwnicIp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckFile extends Command
{
    protected function configure()
    {
        $this->setName('check:file')
            ->setDescription('Check IP by file')
            ->addOption('csv', '-c', InputOption::VALUE_NONE, 'Output CSV')
            ->addArgument('file', InputArgument::REQUIRED, 'IP CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $this->read($input->getArgument('file'));
        $csv = $input->getOption('csv');

        foreach ($file as $ip) {
            $ip = trim($ip);

            if ((new TwnicIp())->isTaiwan($ip)) {
                if ($csv) {
                    $output->writeln("$ip,1");
                } else {
                    $output->writeln("$ip is Taiwan");
                }
            } else {
                if ($csv) {
                    $output->writeln("$ip,0");
                } else {
                    $output->writeln("$ip is NOT Taiwan");
                }
            }
        }

        if ($output->isDebug()) {
            echo 'Memory peak usage: ' . memory_get_peak_usage();
        }

        return 0;
    }

    protected function read($file): Generator
    {
        $handle = fopen($file, 'r+');

        if (false === $handle) {
            throw new \RuntimeException('File error');
        }

        while (($data = fgets($handle)) !== false) {
            yield $data;
        }

        fclose($handle);
    }
}
