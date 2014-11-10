<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ScanCommand extends Command
{
	protected function configure()
    {
        $this->setName('scan')
			->setDescription('Scan the given location for possible security issues')
            ->setDefinition(array(
                new InputOption('target', 'target', InputOption::VALUE_REQUIRED, 'Path to the file/directory'),
                new InputOption('output', 'output', InputOption::VALUE_OPTIONAL, 'Output method [xml]'),
                new InputOption('debug', 'debug', InputOption::VALUE_NONE, 'Show debug output'),
            ))
            ->setHelp(
                'Scan the given location for possible security issues'
            );
    }

    /**
     * Execute the "fix" command
     *
     * @param  InputInterface $input Input object
     * @param  OutputInterface $output Output object
     * @throws \Exception
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$matches = array(
			'type:expr.eval'
		);
		$output = $input->getOption('output');
		if ($output == null) {
			$output = 'xml';
		}
		$target = $input->getOption('target');
		if ($target == null) {
			throw new \Exception('Target directory/file path required! None given...');
		}
		$debug = $input->getOption('debug');

		$scanner = new \Psecio\Parse\Scanner($target);
		$results = $scanner->execute($matches);

		if ($debug !== null) {
			print_r($results);
		}

		$xml = new \Psecio\Parse\Output\Xml();
		echo $xml->generate($results);
    }
}