<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\FileEvent;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;

/**
 * Display phpunit style dots to visualize scan progression
 */
class ConsoleDots extends Subscriber
{
    use OutputTrait;

    /**
     * @var string One charactes status descriptor
     */
    private $status;

    /**
     * @var integer Number of status chars per line
     */
    private $lineLength = 60;

    /**
     * @var integer Number of scanned files
     */
    private $fileCount;

    /**
     * Set number of status chars per line
     *
     * @param  integer $lineLength
     * @return void
     */
    public function setLineLength($lineLength)
    {
        $this->lineLength = $lineLength;
    }

    /**
     * Write header on scan start
     *
     * @return void
     */
    public function onScanStart()
    {
        $this->fileCount = 0;
    }

    /**
     * Set status to valid on file open
     *
     * @param  FileEvent $event
     * @return void
     */
    public function onFileOpen(FileEvent $event)
    {
        $this->fileCount++;
        $this->status = '.';
    }

    /**
     * Write file status on file close
     *
     * @return void
     */
    public function onFileClose()
    {
        $this->write($this->status);
        if ($this->fileCount % $this->lineLength == 0) {
            $this->write("\n");
        }
    }

    /**
     * Set file status to I on file issue
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->status = '<error>I</error>';
    }

    /**
     * Set file status to E on file error
     *
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
    {
        $this->status = '<error>E</error>';
    }
}
