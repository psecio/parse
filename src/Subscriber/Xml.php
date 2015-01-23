<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\Console\Output\OutputInterface;
use XMLWriter;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\ErrorEvent;

/**
 * Xml generating event subscriber
 */
class Xml extends Subscriber
{
    use OutputTrait;

    /**
     * @var XMLWriter Writer used to produce xml
     */
    private $xmlWriter;

    /**
     * Create document at scan start
     *
     * @return void
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
     * @return void
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
     * @return void
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
     * @param  ErrorEvent $event
     * @return void
     */
    public function onFileError(ErrorEvent $event)
    {
        $this->xmlWriter->startElement('error');
        $this->xmlWriter->writeElement('description', $event->getMessage());
        $this->xmlWriter->writeElement('file', $event->getFile()->getPath());
        $this->xmlWriter->endElement();
    }
}
