<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psecio\Parse\Event\Events;

/**
 * Standard console event subscriber
 */
class ConsoleStandard implements EventSubscriberInterface, Events
{
    /**
     * @var OutputInterface Registered output
     */
    private $output;

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
     * Register output interface
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

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
     * Write header on scan start
     *
     * @return void
     */
    public function onScanStart()
    {
        $this->write("Parse: A PHP Security Scanner\n\n");
        $this->fileCount = 0;
    }

    /**
     * Add line break on scan complete
     *
     * @return void
     */
    public function onScanComplete()
    {
        $this->write("\n\n");
    }

    /**
     * Set status to valid on file open
     *
     * @return void
     */
    public function onFileOpen()
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
     * @return void
     */
    public function onFileIssue()
    {
        $this->status = '<error>I</error>';
    }

    /**
     * Set file status to E on file error
     *
     * @return void
     */
    public function onFileError()
    {
        $this->status = '<error>E</error>';
    }

    /**
     * Ignore debug events
     *
     * @return void
     */
    public function onDebug()
    {
    }

    /**
     * Write to console
     *
     * @param  string $format sprintf format string
     * @param  mixed  ...$arg Any number of sprintf arguments
     * @return void
     */
    protected function write()
    {
        $this->output->write(call_user_func_array('sprintf', func_get_args()));
    }
}
