<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Psecio\Parse\Conf\ConfFactory;
use Psecio\Parse\Subscriber\SubscriberFactory;
use Psecio\Parse\Subscriber\ExitCodeCatcher;
use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\MessageEvent;
use Psecio\Parse\RuleFactory;
use Psecio\Parse\CallbackVisitor;
use Psecio\Parse\Scanner;
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
                'Path to scan'
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output format (progress, dots, lines, debug or xml)'
            )
            ->addOption(
                'ignore-paths',
                'i',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of paths to ignore'
            )
            ->addOption(
                'extensions',
                'x',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of file extensions to parse (default: php,phps,phtml,php5)'
            )
            ->addOption(
                'whitelist-rules',
                'w',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of rules to whitelist'
            )
            ->addOption(
                'blacklist-rules',
                'b',
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of rules to blacklist'
            )
            ->addOption(
                'disable-annotations',
                'd',
                InputOption::VALUE_NONE,
                'Skip all annotation-based rule toggles'
            )
            ->addOption(
                'configuration',
                'c',
                InputOption::VALUE_REQUIRED,
                'Read configuration from file'
            )
            ->addOption(
                'no-configuration',
                null,
                InputOption::VALUE_NONE,
                'Ignore default configuration file'
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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conf = (new ConfFactory)->createConf($input, $confFileName);
        $dispatcher = new EventDispatcher;

        (new SubscriberFactory($conf->getFormat(), $output))->addSubscribersTo($dispatcher);

        $exitCode = new ExitCodeCatcher;
        $dispatcher->addSubscriber($exitCode);

        if ($confFileName) {
            $dispatcher->dispatch(Events::DEBUG, new MessageEvent("Reading configurations from $confFileName"));
        }

        $rules = (new RuleFactory($conf->getRuleWhitelist(), $conf->getRuleBlacklist()))->createRuleCollection();

        $dispatcher->dispatch(Events::DEBUG, new MessageEvent("Using ruleset: $rules"));

        $docCommentFactory = new DocCommentFactory();

        $scanner = new Scanner(
            $dispatcher,
            new CallbackVisitor(
                $rules,
                $docCommentFactory,
                !$input->getOption('disable-annotations')
            )
        );

        $scanner->scan(new FileIterator($conf->getPaths(), $conf->getIgnorePaths(), $conf->getExtensions()));

        return $exitCode->getExitCode();
    }
}
