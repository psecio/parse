<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ListTestsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('list-tests')
            ->setDescription('List the current checks and their summaries')
            ->setHelp(
                'List the current checks and their summaries'
            );
    }

    /**
     * Execute the "list" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws \Exception
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = new \Psecio\Parse\Scanner(null);
        $tests = $scanner->getTests(__DIR__.'/Tests');
        $count = 0;

        $output->writeLn("\n"
            .str_pad('ID', 3, ' ').' | '
            .str_pad('Name', 36, ' ')
            .'| Description'
        );
        $output->writeLn(str_repeat('=', 80));
        foreach ($tests as $index => $test) {
            $count++;
            $class = str_replace('Psecio\\Parse\\Tests\\', '', get_class($test));
            $output->writeLn(
                str_pad($index, 3, ' ').' | '
                .str_pad($class, 35, ' ')
                .' | '.$test->getDescription()
            );
        }
        $output->writeLn("\n".$count." Tests\n");
    }
}
