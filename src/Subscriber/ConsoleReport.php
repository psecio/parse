<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;

/**
 * Print report at scan complete
 */
class ConsoleReport extends Subscriber
{
    use OutputTrait;

    /**
     * @var integer Number of scanned files
     */
    private $fileCount;

    /**
     * @var array List of failures
     */
    private $issues;

    /**
     * @var array List of errors
     */
    private $errors;

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
     * @param  FileEvent $event
     * @return void
     */
    public function onFileOpen(FileEvent $event)
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
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
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
        return "\n\n" . ($this->errors || $this->issues ? $this->getFailureReport() : $this->getPassReport());
    }

    /**
     * Get info on scanned files
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
     * Get info on errors and issues
     *
     * @return string
     */
    private function getFailureReport()
    {
        return $this->getErrorReport()
            . $this->getIssueReport()
            . sprintf(
                "<error>FAILURES!</error>\n<error>Scanned: %d, Errors: %d, Issues: %d.</error>",
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
        $str = '';

        if ($this->issues) {
            $str .= $this->pluralize(
                "There was %d issue\n\n",
                "There were %d issues\n\n",
                count($this->issues)
            );
        }

        foreach ($this->issues as $index => $issueEvent) {
            $str .= sprintf(
                "<comment>%d) %s on line %d</comment>\n%s\n<error>> %s</error>\n",
                $index + 1,
                $issueEvent->getFile()->getPath(),
                $issueEvent->getNode()->getLine(),
                $issueEvent->getRule()->getDescription(),
                implode("\n> ", $issueEvent->getFile()->fetchNode($issueEvent->getNode()))
            );
            $str .= "For more information execute 'psecio-parse rules {$issueEvent->getRule()->getName()}'\n\n";
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
        $str = '';

        if ($this->errors) {
            $str .= $this->pluralize(
                "There was %d error\n\n",
                "There were %d errors\n\n",
                count($this->errors)
            );
        }

        foreach ($this->errors as $index => $errorEvent) {
            $str .= sprintf(
                "<comment>%d) %s</comment>\n<error>%s</error>\n\n",
                $index + 1,
                $errorEvent->getFile()->getPath(),
                $errorEvent->getMessage()
            );
        }

        return $str;
    }
}
