<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Psecio\Parse\Scanner;
use Exception;

/**
 * The main command: scans specified paths for possible security issues
 */
class ScanCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('scan')
            ->setDescription('Scans specified paths for possible security issues')
            ->addArgument('path', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Path to the file/directory to scan', [getcwd()])
            ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Output method (txt or xml)', 'txt')
            ->addOption('tests', null, InputOption::VALUE_REQUIRED, 'Test(s) to execute', '')
            ->addOption('exclude', null, InputOption::VALUE_REQUIRED, 'Test(s) to exclude', '')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command scans specified paths for possible security issues:

  <info>php %command.full_name% /path/to/src</info>
EOF
            );
    }

    /**
     * Execute the "scan" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws Exception If output format is not valid
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = (new Scanner)->execute(
            $input->getArgument('path'),
            array_filter(explode(',', $input->getOption('tests'))),
            array_filter(explode(',', $input->getOption('exclude')))
        );

        switch (strtolower($input->getOption('output'))) {
            case 'txt':
                $txt = new \Psecio\Parse\Output\Console();
                $output->write($txt->generate($results));
                break;
            case 'xml':
                $xml = new \Psecio\Parse\Output\Xml();
                $output->write($xml->generate($results));
                break;
            default:
                throw new Exception("Unknown output method '{$input->getOption('output')}'");
        }
    }
}
