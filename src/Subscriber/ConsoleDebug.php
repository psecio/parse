<?php

namespace Psecio\Parse\Subscriber;

use Psecio\Parse\Event\MessageEvent;

/**
 * Debug console event subscriber
 */
class ConsoleDebug extends ConsoleLines
{
    /**
     * @var double Unix timestamp at scan start
     */
    private $startTime;

    /**
     * Save timestamp at scan start
     *
     * @return void
     */
    public function onScanStart()
    {
        parent::onScanStart();
        $this->write("<comment>[DEBUG] Starting scan</comment>\n");
        $this->startTime = microtime(true);
    }

    /**
     * Write time elapsed at scan complete
     *
     * @return void
     */
    public function onScanComplete()
    {
        $this->write(
            "<comment>[DEBUG] Scan completed in %f seconds</comment>\n",
            microtime(true) - $this->startTime
        );
        parent::onScanComplete();
    }

    /**
     * Write debug message
     *
     * @param  MessageEvent $event
     * @return void
     */
    public function onDebug(MessageEvent $event)
    {
        $this->write(
            "<comment>[DEBUG] %s</comment>\n",
            $event->getMessage()
        );
    }
}
