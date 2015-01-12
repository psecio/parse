<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XMLWriter;
use Psecio\Parse\Event\Events;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Xml generating event subscriber
 */
class Xml implements EventSubscriberInterface, Events
{
    /**
     * @var OutputInterface Registered output
     */
    private $output;

    /**
     * @var XMLWriter Writer used to produce xml
     */
    private $xmlWriter;

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
     * Returns an array of event names this subscriber wants to listen to
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            self::SCAN_START => 'onScanStart',
            self::SCAN_COMPLETE => 'onScanComplete',
            self::FILE_ISSUE => 'onFileIssue',
            self::FILE_ERROR => 'onFileError'
        ];
    }

    /**
     * Create document at scan start
     *
     * @return null
     */
    public function onScanStart()
    {
        $this->xmlWriter = new XMLWriter;
        $this->xmlWriter->openMemory();
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startElement('results');
    }

    /**
     * Output document at scan complete
     *
     * @return null
     */
    public function onScanComplete()
    {
        $this->xmlWriter->endElement();
        $this->xmlWriter->endDocument();
        $this->output->writeln(
            $this->xmlWriter->flush(),
            OutputInterface::OUTPUT_RAW
        );
    }

    /**
     * Write issue to document
     *
     * @param  IssueEvent $event
     * @return null
     */
    public function onFileIssue(IssueEvent $event)
    {
        $this->xmlWriter->startElement('issue');
        $this->xmlWriter->writeElement('type', $event->getRule()->getName());
        $this->xmlWriter->writeElement('description', $event->getRule()->getDescription());
        $this->xmlWriter->writeElement('file', $event->getFile()->getPath());
        $this->xmlWriter->writeElement('line', $event->getNode()->getLine());
        $this->xmlWriter->startElement('source');
        $this->xmlWriter->writeCData(implode("\n", $event->getFile()->fetchNode($event->getNode())));
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
    }

    /**
     * Write error to document
     *
     * @param  MessageEvent $event
     * @return null
     */
    public function onFileError(MessageEvent $event)
    {
        $this->xmlWriter->startElement('error');
        $this->xmlWriter->writeElement('description', $event->getMessage());
        $this->xmlWriter->writeElement('file', $event->getFile()->getPath());
        $this->xmlWriter->endElement();
    }
}
