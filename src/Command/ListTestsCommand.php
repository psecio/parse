<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psecio\Parse\TestInterface;
use Psecio\Parse\TestCollection;
use Psecio\Parse\RuleFactory;

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
            ->setHelp(
                "List current checks and their summaries:\n\n  <info>%command.full_name%</info>\n"
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

        $testCollection = (new RuleFactory)->createRuleCollection();

        $output->writeln(" " . count($testCollection) . " found");
        $output->writeln("<comment>Listing:</comment>");

        $colWidth = $this->getTestNameColWidth($testCollection);

        foreach ($testCollection as $test) {
            $output->write(" <info>" . str_pad($test->getName(), $colWidth) . "</info> ");
            $output->writeln($test->getDescription());
        }
    }

    /**
     * Get length of the longest test name in collection
     *
     * @param  TestCollection $testCollection
     * @return int
     */
    public function getTestNameColWidth(TestCollection $testCollection)
    {
        return max(
            array_map(
                function (TestInterface $test) {
                    return strlen($test->getName());
                },
                $testCollection->toArray()
            )
        );
    }
}
