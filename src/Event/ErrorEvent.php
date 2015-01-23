<?php

namespace Psecio\Parse\Event;

use Psecio\Parse\File;

/**
 * Event containing a File and a message
 */
class ErrorEvent extends FileEvent
{
    /**
     * @var string Message
     */
    private $message;

    /**
     * Set File and message
     *
     * @param string $message
     * @param File $file
     */
    public function __construct($message, File $file)
    {
        parent::__construct($file);
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
