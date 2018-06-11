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
use Psecio\Parse\Subscriber\Json;
use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\MessageEvent;
use Psecio\Parse\RuleFactory;
use Psecio\Parse\RuleInterface;
use Psecio\Parse\Scanner;
use Psecio\Parse\CallbackVisitor;
use Psecio\Parse\FileIterator;
use Psecio\Parse\DocComment\DocCommentFactory;
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
                'Paths to scan',
                []
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output format (progress, dots, xml or json)',
                'progress'
            )
            ->addOption(
                'ignore-paths',
                'i',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of paths to ignore',
                ''
            )
            ->addOption(
                'extensions',
                'x',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of file extensions to parse',
                'php,phps,phtml,php5'
            )
            ->addOption(
                'whitelist-rules',
                'w',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of rules to use',
                ''
            )
            ->addOption(
                'blacklist-rules',
                'b',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of rules to skip',
                ''
            )
            ->addOption(
                'disable-annotations',
                'd',
                InputOption::VALUE_NONE,
                'Skip all annotation-based rule toggles.'
            )
            ->setHelp(
                "Scan paths for possible security issues:\n\n  <info>psecio-parse %command.name% /path/to/src</info>\n"
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

        $fileIterator = new FileIterator(
            $input->getArgument('path'),
            $this->parseCsv($input->getOption('ignore-paths')),
            $this->parseCsv($input->getOption('extensions'))
        );

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
            case 'json':
                $dispatcher->addSubscriber(new Json($output));
                break;
            default:
                throw new RuntimeException("Unknown output format '{$input->getOption('format')}'");
        }

        $ruleFactory = new RuleFactory(
            $this->parseCsv($input->getOption('whitelist-rules')),
            $this->parseCsv($input->getOption('blacklist-rules'))
        );

        $ruleCollection = $ruleFactory->createRuleCollection();

        $ruleNames = implode(',', array_map(
            function (RuleInterface $rule) {
                return $rule->getName();
            },
            $ruleCollection->toArray()
        ));

        $dispatcher->dispatch(Events::DEBUG, new MessageEvent("Using ruleset $ruleNames"));

        $docCommentFactory = new DocCommentFactory();

        $scanner = new Scanner(
            $dispatcher,
            new CallbackVisitor(
                $ruleCollection,
                $docCommentFactory,
                !$input->getOption('disable-annotations')
            )
        );
        $scanner->scan($fileIterator);

        return $exitCode->getExitCode();
    }

    /**
     * Parse comma-separated values from string
     *
     * Using array_filter ensures that an empty array is returned when an empty
     * string is parsed.
     *
     * @param  string $string
     * @return array
     */
    public function parseCsv($string)
    {
        return array_filter(explode(',', $string));
    }
}
