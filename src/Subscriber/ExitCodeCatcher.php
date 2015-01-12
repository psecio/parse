<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Capture the exit status code of a scan
 */
class ExitCodeCatcher implements EventSubscriberInterface
{
    use Helper\SubscriberTrait;

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
     * @return null
     */
    public function onFileIssue()
    {
        $this->exitCode = 1;
    }

    /**
     * Set exit code 1 on file error
     *
     * @return null
     */
    public function onFileError()
    {
        $this->exitCode = 1;
    }
}
