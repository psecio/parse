<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psecio\Parse\Scanner;
use Psecio\Parse\Test;
use Psecio\Parse\TestCollection;

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
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = new Scanner(null);
        $tests = $scanner->getTests(__DIR__.'/Tests');

        $colWidth = $this->getTestNameColWidth($tests);

        $output->writeLn("\n"
            .str_pad('ID', 3, ' ').' | '
            .str_pad('Name', $colWidth, ' ')
            .' | Description'
        );
        $output->writeLn(str_repeat('=', 80));
        foreach ($tests as $index => $test) {
            $output->writeLn(
                str_pad($index, 3, ' ').' | '
                .str_pad($test->getName(), $colWidth, ' ')
                .' | '.$test->getDescription()
            );
        }
        $output->writeLn("\n".count($tests)." Tests\n");
    }

    /**
     * Get length of the longest test name in collection
     *
     * @param  TestCollection $tests
     * @return int
     */
    public function getTestNameColWidth(TestCollection $tests)
    {
        return max(
            array_map(
                function (Test $test) {
                    return strlen($test->getName());
                },
                $tests->toArray()
            )
        );
    }
}
