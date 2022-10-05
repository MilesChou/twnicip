<?php

namespace App\Commands;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Internet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command
{
    protected function configure()
    {
        $this->setName('generate')
            ->setDescription('Generate faker IP')
            ->addArgument('amount', InputArgument::OPTIONAL, 'Amount', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $amount = $input->getArgument('amount');
        $faker = Factory::create('zh_TW');

        for ($i = 0; $i < $amount; $i++) {
            $output->writeln($faker->ipv4);
        }

        return 0;
    }
}