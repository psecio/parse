<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScanCommand extends Command
{
	protected function configure()
    {
        $this->setName('scan')
			->setDescription('Scan the given location for possible security issues')
            ->setDefinition(array(
                new InputArgument('target', InputArgument::REQUIRED, 'Path to the file/directory to scan'),
                new InputOption('output', 'output', InputOption::VALUE_OPTIONAL, 'Output method (txt or xml)', 'txt'),
                new InputOption('debug', 'debug', InputOption::VALUE_NONE, 'Show debug output'),
                new InputOption('tests', 'tests', InputOption::VALUE_REQUIRED, 'Test(s) to execute', ''),
                new InputOption('exclude', 'exclude', InputOption::VALUE_REQUIRED, 'Test(s) to exclude', ''),
            ))
            ->setHelp(
                'Scan the given location for possible security issues'
            );
    }

    /**
     * Execute the "scan" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws \Exception
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$scanner = new \Psecio\Parse\Scanner($input->getArgument('target'));

		$results = $scanner->execute(
            $input->getOption('debug'),
            array_filter(explode(',', $input->getOption('tests'))),
            array_filter(explode(',', $input->getOption('exclude')))
        );

		if ($input->getOption('debug')) {
			// print_r($results);
		}

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
                throw new \Exception("Unknown output method '{$input->getOption('output')}'");
        }
    }
}
