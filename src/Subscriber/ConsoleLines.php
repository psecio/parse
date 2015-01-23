<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;

/**
 * Display descriptive lines to visualize scan progression
 */
class ConsoleLines extends Subscriber
{
    use OutputTrait;

    /**
     * Write path on file open
     *
     * @param  FileEvent $event
     * @return void
     */
    public function onFileOpen(FileEvent $event)
    {
        $this->write("[PARSE] %s\n", $event->getFile()->getPath());
    }

    /**
     * Write issue as one line
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->write(
            "<error>[ISSUE] [%s] On line %d in %s</error>\n",
            $event->getRule()->getName(),
            $event->getNode()->getLine(),
            $event->getFile()->getPath()
        );
    }

    /**
     * Write error as one line
     *
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
    {
        $this->write(
            "<error>[ERROR] %s in %s</error>\n",
            $event->getMessage(),
            $event->getFile()->getPath()
        );
    }
}
