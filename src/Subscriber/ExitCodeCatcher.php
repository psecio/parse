<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Capture the exit status code of a scan
 */
class ExitCodeCatcher extends Subscriber
{
    /**
     * @var integer Suggested exit code
     */
    private $exitCode = 0;

    /**
     * Get suggested exit code
     *
     * @return integer
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Set exit code 1 on file issue
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->exitCode = 1;
    }

    /**
     * Set exit code 1 on file error
     *
     * @param  MessageEvent $event
     * @return void
     */
    public function onFileError(MessageEvent $event)
    {
        $this->exitCode = 1;
    }
}
