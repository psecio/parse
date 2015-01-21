<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Psecio\Parse\RuleFactory;
use Psecio\Parse\RuleCollection;
use Psecio\Parse\RuleInterface;

/**
 * Command for displaying information about the pesecio-parse ruleset
 */
class RulesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('rules')
            ->setDescription('Display information about the pesecio-parse ruleset')
            ->addArgument(
                'rule',
                InputArgument::OPTIONAL,
                'Display info about rule'
            )
            ->setHelp(
                "Display info about rules:\n\n  <info>psecio-parse %command.name% [name-of-rule]</info>\n"
            );
    }

    /**
     * Execute the "rules" command
     *
     * @param  InputInterface  $input  Input object
     * @param  OutputInterface $output Output object
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $rules = (new RuleFactory)->createRuleCollection();

        if ($rulename = $input->getArgument('rule')) {
            $this->describeRule($rules->get($rulename), $output);
            return;
        }

        $this->listRules($rules, $output);
    }

    /**
     * List all bundled rules
     *
     * @param  RuleCollection  $rules
     * @param  OutputInterface $output
     * @return void
     */
    public function listRules(RuleCollection $rules, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setStyle('compact');
        $table->setHeaders(['Name', 'Description']);

        foreach ($rules as $rule) {
            $table->addRow(
                [
                    '<comment>'.$rule->getName().'</comment>',
                    $rule->getDescription()
                ]
            );
        }

        $table->render();
        $output->writeln("\n <info>Use 'psecio-parse rules name-of-rule' for more info about a specific rule</info>");
    }

    /**
     * Display rule description
     *
     * @param  RuleInterface   $rule
     * @param  OutputInterface $output
     * @return void
     */
    public function describeRule(RuleInterface $rule, OutputInterface $output)
    {
        $output->getFormatter()->setStyle(
            'strong',
            new OutputFormatterStyle(null, null, ['bold', 'reverse'])
        );
        $output->getFormatter()->setStyle(
            'em',
            new OutputFormatterStyle('yellow', null, ['bold'])
        );
        $output->getFormatter()->setStyle(
            'code',
            new OutputFormatterStyle('green')
        );
        $output->writeln("<strong>{$rule->getName()}</strong>\n");
        $output->writeln("{$rule->getLongDescription()}\n");
    }
}
