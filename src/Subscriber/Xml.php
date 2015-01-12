<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XMLWriter;
use Psecio\Parse\Event\IssueEvent;
use Psecio\Parse\Event\MessageEvent;

/**
 * Xml generating event subscriber
 */
class Xml implements EventSubscriberInterface
{
    use Helper\SubscriberTrait, Helper\OutputTrait;

    /**
     * @var XMLWriter Writer used to produce xml
     */
    private $xmlWriter;

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
        $attrs = $event->getNode()->getAttributes();
        $this->xmlWriter->startElement('issue');
        $this->xmlWriter->writeElement('type', $event->getTest()->getName());
        $this->xmlWriter->writeElement('description', $event->getTest()->getDescription());
        $this->xmlWriter->writeElement('file', $event->getFile()->getPath());
        $this->xmlWriter->writeElement('line', $attrs['startLine']);
        $this->xmlWriter->startElement('source');
        $this->xmlWriter->writeCData(trim(implode("\n", $event->getFile()->getLines($attrs['startLine']))));
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
