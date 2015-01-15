<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Helper\ProgressBar;
use Psecio\Parse\Subscriber\ExitCodeCatcher;
use Psecio\Parse\Subscriber\ConsoleDots;
use Psecio\Parse\Subscriber\ConsoleProgressBar;
use Psecio\Parse\Subscriber\ConsoleLines;
use Psecio\Parse\Subscriber\ConsoleDebug;
use Psecio\Parse\Subscriber\ConsoleReport;
use Psecio\Parse\Subscriber\Xml;
use Psecio\Parse\RuleFactory;
use Psecio\Parse\Scanner;
use Psecio\Parse\CallbackVisitor;
use Psecio\Parse\FileIterator;
use RuntimeException;

/**
 * The main command, scan paths for possible security issues
 */
class ScanCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('scan')
            ->setDescription('Scans paths for possible security issues')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL|InputArgument::IS_ARRAY,
                'Path to scan.',
                [getcwd()]
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                'Output format (progress, dots or xml)',
                'progress'
            )
            ->addOption(
                'include-rules',
                null,
                InputOption::VALUE_REQUIRED,
                'Comma separated list of rules to include when scanning.',
                ''
            )
            ->addOption(
                'exclude-rules',
                null,
                InputOption::VALUE_REQUIRED,
                'Comma separated list of rules to exclude when scanning.',
                ''
            )
            ->setHelp(
                "Scan paths for possible security issues:\n\n  <info>%command.full_name% /path/to/src</info>\n"
            );
    }

    /**
     * Execute the "scan" command
     *
     * @param  InputInterface   $input Input object
     * @param  OutputInterface  $output Output object
     * @throws RuntimeException If output format is not valid
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dispatcher = new EventDispatcher;

        $exitCode = new ExitCodeCatcher;
        $dispatcher->addSubscriber($exitCode);

        $fileIterator = new FileIterator($input->getArgument('path'));

        $format = strtolower($input->getOption('format'));
        switch ($format) {
            case 'dots':
            case 'progress':
                $output->writeln("<info>Parse: A PHP Security Scanner</info>\n");
                if ($output->isVeryVerbose()) {
                    $dispatcher->addSubscriber(
                        new ConsoleDebug($output)
                    );
                } elseif ($output->isVerbose()) {
                    $dispatcher->addSubscriber(
                        new ConsoleLines($output)
                    );
                } elseif ('progress' == $format && $output->isDecorated()) {
                    $dispatcher->addSubscriber(
                        new ConsoleProgressBar(new ProgressBar($output, count($fileIterator)))
                    );
                } else {
                    $dispatcher->addSubscriber(
                        new ConsoleDots($output)
                    );
                }
                $dispatcher->addSubscriber(new ConsoleReport($output));
                break;
            case 'xml':
                $dispatcher->addSubscriber(new Xml($output));
                break;
            default:
                throw new RuntimeException("Unknown output format '{$input->getOption('format')}'");
        }

        $ruleFactory = new RuleFactory(
            array_filter(explode(',', $input->getOption('include-rules'))),
            array_filter(explode(',', $input->getOption('exclude-rules')))
        );

        $scanner = new Scanner($dispatcher, new CallbackVisitor($ruleFactory->createRuleCollection()));
        $scanner->scan($fileIterator);

        return $exitCode->getExitCode();
    }
}
