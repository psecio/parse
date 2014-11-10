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
    	$target = $input->getOption('target');
    	if ($target == null) {
    		throw new \Exception('Target directory/file path required! None given...');
    	}

    	$scanner = new \Psecio\Parse\Scanner($target);
    	$results = $scanner->execute($matches);

    	print_r($results);
    }
}