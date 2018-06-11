<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\Console\Output\OutputInterface;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;

/**
 * Xml generating event subscriber
 */
class Json extends Subscriber
{
    use OutputTrait;

    private $issues = [];

    /**
     * Create document at scan start
     *
     * @return void
     */
    public function onScanStart()
    {
        // nothing to see
    }

    /**
     * Output document at scan complete
     *
     * @return void
     */
    public function onScanComplete()
    {
        echo json_encode(['results' => $this->issues]);
    }

    /**
     * Write issue to document
     *
     * @param  IssueEvent $event
     * @return void
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->issues[] = [
            'type' => $event->getRule()->getName(),
            'description' => $event->getRule()->getDescription(),
            'file' => $event->getFile()->getPath(),
            'line' => $event->getNode()->getLine(),
            'source' => implode("\n", $event->getFile()->fetchNode($event->getNode()))
        ];
    }

    /**
     * Write error to document
     *
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
    {
        $this->issues['error'] = [
            'description' => $event->getMessage(),
            'file' => $event->getFile()->getPath()
        ];
    }
}
