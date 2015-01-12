<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psecio\Parse\RuleInterface;
use Psecio\Parse\RuleCollection;
use Psecio\Parse\RuleFactory;

/**
 * Command for listing current checks and their summaries
 */
class InfoCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('info')
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
        $output->write("<comment>Searching for rules:</comment>");

        $ruleCollection = (new RuleFactory)->createRuleCollection();

        $output->writeln(" " . count($ruleCollection) . " found");
        $output->writeln("<comment>Listing:</comment>");

        $colWidth = $this->getNameColWidth($ruleCollection);

        foreach ($ruleCollection as $rule) {
            $output->write(" <info>" . str_pad($rule->getName(), $colWidth) . "</info> ");
            $output->writeln($rule->getDescription());
        }
    }

    /**
     * Get length of the longest name in collection
     *
     * @param  RuleCollection $ruleCollection
     * @return int
     */
    private function getNameColWidth(RuleCollection $ruleCollection)
    {
        return max(
            array_map(
                function (RuleInterface $rule) {
                    return strlen($rule->getName());
                },
                $ruleCollection->toArray()
            )
        );
    }
}
