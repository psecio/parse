<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Display descriptive lines to visualize scan progression
 */
class ConsoleLines implements EventSubscriberInterface
{
    use Helper\SubscriberTrait, Helper\OutputTrait;

    /**
     * Write path on file open
     *
     * @param  FileEvent $event
     * @return null
     */
    public function onFileOpen(FileEvent $event)
    {
        $this->write("[PARSE] %s\n", $event->getFile()->getPath());
    }

    /**
     * Write issue as one line
     *
     * @param  IssueEvent $event
     * @return null
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->write(
            "<error>[ISSUE] [%s] On line %d in %s</error>\n",
            $event->getTest()->getName(),
            $event->getNode()->getLine(),
            $event->getFile()->getPath()
        );
    }

    /**
     * Write error as one line
     *
     * @param  MessageEvent $event
     * @return null
     */
    public function onFileError(MessageEvent $event)
    {
        $this->write(
            "<error>[ERROR] %s in %s</error>\n",
            $event->getMessage(),
            $event->getFile()->getPath()
        );
    }
}
