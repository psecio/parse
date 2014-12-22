<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psecio\Parse\Scanner;
use Psecio\Parse\Test;
use Psecio\Parse\TestCollection;

/**
 * Command for listing current checks and their summaries
 */
class ListTestsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('list-tests')
            ->setDescription('List the current checks and their summaries')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command displays the current checks and their summaries:

  <info>php %command.full_name%</info>
EOF
            );
    }

    /**
     * Execute the "list" command
     *
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("<comment>Searching for tests:</comment>");

        $scanner = new Scanner(null);
        $tests = $scanner->getTests(__DIR__.'/Tests');

        $output->writeln(" " . count($tests) . " found");
        $output->writeln("<comment>Listing:</comment>");

        $colWidth = $this->getTestNameColWidth($tests);

        foreach ($tests as $test) {
            $output->write(" <info>" . str_pad($test->getName(), $colWidth) . "</info> ");
            $output->writeln($test->getDescription());
        }
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
