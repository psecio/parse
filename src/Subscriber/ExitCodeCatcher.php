<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psecio\Parse\Event\Events;

/**
 * Capture the exit status code of a scan
 */
class ExitCodeCatcher implements EventSubscriberInterface, Events
{
    /**
     * @var integer Suggested exit code
     */
    private $exitCode = 0;

    /**
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            self::FILE_ISSUE => 'onFileIssue',
            self::FILE_ERROR => 'onFileError'
        ];
    }

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
     * @return void
     */
    public function onFileIssue()
    {
        $this->exitCode = 1;
    }

    /**
     * Set exit code 1 on file error
     *
     * @return void
     */
    public function onFileError()
    {
        $this->exitCode = 1;
    }
}
