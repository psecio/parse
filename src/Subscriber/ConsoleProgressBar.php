<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Display a progress bar to visualize scan progression
 */
class ConsoleProgressBar implements EventSubscriberInterface, Events
{
    /**
     * @var OutputInterface Registered output
     */
    private $output;

    /**
     * @var ProgressBar The progress bra
     */
    private $progressBar;

    /**
     * Register output interface
     *
     * @param OutputInterface $output
     * @param integer $fileCount Total number of files to scan
     */
    public function __construct(OutputInterface $output, ProgressBar $progressBar = null)
    {
        $this->output = $output;
        $this->progressBar = $progressBar ?: new ProgressBar($output);
        $this->progressBar->setFormat(
            $this->progressBar->getMaxSteps()
            ? '%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%'
            : '%current% [%bar%] %elapsed:6s% %memory:6s%'
        );
    }

    /**
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            self::SCAN_START => 'onScanStart',
            self::SCAN_COMPLETE => 'onScanComplete',
            self::FILE_CLOSE => 'onFileClose',
        ];
    }

    /**
     * Write header on scan start
     *
     * @return null
     */
    public function onScanStart()
    {
        $this->output->writeln("<info>Parse: A PHP Security Scanner</info>\n");
        $this->progressBar->start();
    }

    /**
     * Finish progress bar on scan complete
     *
     * @return null
     */
    public function onScanComplete()
    {
        $this->progressBar->finish();
        $this->output->writeln("\n");
    }

    /**
     * Advance progress bar on file close
     *
     * @return null
     */
    public function onFileClose()
    {
        $this->progressBar->advance();
    }
}
