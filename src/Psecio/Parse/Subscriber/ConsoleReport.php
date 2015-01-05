<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Print report to output at scan complete
 */
class ConsoleReport implements EventSubscriberInterface, Events
{
    /**
     * @var OutputInterface Registered output
     */
    private $output;

    /**
     * @var integer Number of scanned files
     */
    private $fileCount;

    /**
     * @var array List of test failures
     */
    private $issues;

    /**
     * @var array List of scan errors
     */
    private $errors;

    /**
     * Register output interface
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
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
            self::FILE_OPEN => 'onFileOpen',
            self::FILE_ISSUE => 'onFileIssue',
            self::FILE_ERROR => 'onFileError'
        ];
    }

    /**
     * Reset values on scan start
     *
     * @return void
     */
    public function onScanStart()
    {
        $this->fileCount = 0;
        $this->issues = [];
        $this->errors = [];
    }

    /**
     * Write report on scan complete
     *
     * @return void
     */
    public function onScanComplete()
    {
        $this->output->writeln($this->getReport());
    }

    /**
     * Increment files scanned counter
     *
     * @return void
     */
    public function onFileOpen()
    {
        $this->fileCount++;
    }

    /**
     * Save issue event
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->issues[] = $event;
    }

    /**
     * Save error event
     *
     * @param  MessageEvent $event
     * @return void
     */
    public function onFileError(MessageEvent $event)
    {
        $this->errors[] = $event;
    }

    /**
     * Format using different formats pending on $number
     *
     * @param  string $singular Format used in singularis
     * @param  string $plural   Format used in pluralis
     * @param  int|float $count Format argument and numerus marker
     * @return string
     */
    private function pluralize($singular, $plural, $count)
    {
        return $count == 1 ? sprintf($singular, $count) : sprintf($plural, $count);
    }

    /**
     * Get report
     *
     * @return string
     */
    private function getReport()
    {
        return $this->errors || $this->issues ? $this->getFailureReport() : $this->getPassReport();
    }

    /**
     * Get report for all tests pass
     *
     * @return string
     */
    private function getPassReport()
    {
        return $this->pluralize(
            "<info>OK (%d file scanned)</info>",
            "<info>OK (%d files scanned)</info>",
            $this->fileCount
        );
    }

    /**
     * Get failure report
     *
     * @return string
     */
    private function getFailureReport()
    {
        return $this->getIssueReport()
            . "\n--\n\n"
            . $this->getErrorReport()
            . "\n<error>FAILURES!</error>\n"
            . sprintf(
                "<error>Scanned: %d, Errors: %d, Issues: %d.</error>",
                $this->fileCount,
                count($this->errors),
                count($this->issues)
            );
    }

    /**
     * Get issue report
     *
     * @return string
     */
    private function getIssueReport()
    {
        $str = $this->pluralize(
            "There was %d issue\n",
            "There were %d issues\n",
            count($this->issues)
        );

        foreach ($this->issues as $index => $issueEvent) {
            $attrs = $issueEvent->getNode()->getAttributes();
            $str .= sprintf(
                "\n%d) %s:%d\n%s\n> %s\n",
                $index + 1,
                $issueEvent->getFile()->getPath(),
                $attrs['startLine'],
                $issueEvent->getTest()->getDescription(),
                trim(implode("\n> ", $issueEvent->getFile()->getLines($attrs['startLine'])))
            );
        }

        return $str;
    }

    /**
     * Get error report
     *
     * @return string
     */
    private function getErrorReport()
    {
        $str = $this->pluralize(
            "There was %d error\n",
            "There were %d errors\n",
            count($this->errors)
        );

        foreach ($this->errors as $index => $errorEvent) {
            $str .= sprintf(
                "\n%d) %s\n%s\n",
                $index + 1,
                $errorEvent->getFile()->getPath(),
                $errorEvent->getMessage()
            );
        }

        return $str;
    }
}
