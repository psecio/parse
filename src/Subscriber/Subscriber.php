<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Empty subscriber, subclass and override desired event methods
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class Subscriber implements EventSubscriberInterface, Events
{
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
            self::FILE_CLOSE => 'onFileClose',
            self::FILE_ISSUE => 'onFileIssue',
            self::FILE_ERROR => 'onFileError',
            self::DEBUG => 'onDebug'
        ];
    }

    /**
     * Empty on scan start method
     *
     * @return void
     */
    public function onScanStart()
    {
    }

    /**
     * Empty on scan complete method
     *
     * @return void
     */
    public function onScanComplete()
    {
    }

    /**
     * Empty on file open method
     *
     * @param  FileEvent $event
     * @return void
     */
    public function onFileOpen(FileEvent $event)
    {
    }

    /**
     * Empty on file close method
     *
     * @return void
     */
    public function onFileClose()
    {
    }

    /**
     * Empty on file issue method
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
    }

    /**
     * Empty on file error method
     *
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
    {
    }

    /**
     * Empty on debug method
     *
     * @param  MessageEvent $event
     * @return void
     */
    public function onDebug(MessageEvent $event)
    {
    }
}
