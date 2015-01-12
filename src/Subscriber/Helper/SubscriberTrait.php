<?php

namespace Psecio\Parse\Subscriber\Helper;

use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Helper to simplify event subscription
 */
trait SubscriberTrait
{
    /**
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::SCAN_START => 'onScanStart',
            Events::SCAN_COMPLETE => 'onScanComplete',
            Events::FILE_OPEN => 'onFileOpen',
            Events::FILE_CLOSE => 'onFileClose',
            Events::FILE_ISSUE => 'onFileIssue',
            Events::FILE_ERROR => 'onFileError',
            Events::DEBUG => 'onDebug'
        ];
    }

    /**
     * Empty on scan start method
     *
     * @return null
     */
    public function onScanStart()
    {
    }

    /**
     * Empty on scan complete method
     *
     * @return null
     */
    public function onScanComplete()
    {
    }

    /**
     * Empty on file open method
     *
     * @param  FileEvent $event
     * @return null
     */
    public function onFileOpen(FileEvent $event)
    {
    }

    /**
     * Empty on file close method
     *
     * @return null
     */
    public function onFileClose()
    {
    }

    /**
     * Empty on file issue method
     *
     * @param  IssueEvent $event
     * @return null
     */
    public function onFileIssue(IssueEvent $event)
    {
    }

    /**
     * Empty on file error method
     *
     * @param  MessageEvent $event
     * @return null
     */
    public function onFileError(MessageEvent $event)
    {
    }

    /**
     * Empty on debug method
     *
     * @param  MessageEvent $event
     * @return null
     */
    public function onDebug(MessageEvent $event)
    {
    }
}
