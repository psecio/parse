<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RuntimeException;

/**
 * Create subscribers based on format and output settings
 */
class SubscriberFactory
{
    /**
     * Progress bar format identifier
     */
    const FORMAT_PROGRESS = 'progress';

    /**
     * Dots format identifier
     */
    const FORMAT_DOTS = 'dots';

    /**
     * Lines format identifier
     */
    const FORMAT_LINES = 'lines';

    /**
     * Debug format identifier
     */
    const FORMAT_DEBUG = 'debug';

    /**
     * XML format identifier
     */
    const FORMAT_XML = 'xml';

    /**
     * Verbose verbosity identifier
     */
    const VERBOSITY_VERBOSE = 1;

    /**
     * Debug verbosity identifier
     */
    const VERBOSITY_DEBUG = 2;

    /**
     * @var array Format transitions made based on output verbosity
     */
    private $formatTransitions = [
        self::FORMAT_PROGRESS => [
            self::VERBOSITY_VERBOSE => self::FORMAT_LINES,
            self::VERBOSITY_DEBUG => self::FORMAT_DEBUG
        ],
        self::FORMAT_DOTS => [
            self::VERBOSITY_VERBOSE => self::FORMAT_LINES,
            self::VERBOSITY_DEBUG => self::FORMAT_DEBUG
        ],
        self::FORMAT_LINES => [
            self::VERBOSITY_VERBOSE => self::FORMAT_LINES,
            self::VERBOSITY_DEBUG => self::FORMAT_DEBUG
        ],
        self::FORMAT_DEBUG => [
            self::VERBOSITY_VERBOSE => self::FORMAT_DEBUG,
            self::VERBOSITY_DEBUG => self::FORMAT_DEBUG
        ],
        self::FORMAT_XML => [
            self::VERBOSITY_VERBOSE => self::FORMAT_XML,
            self::VERBOSITY_DEBUG => self::FORMAT_XML
        ]
    ];

    /**
     * @var array Maps subscribers to format identifiers
     */
    private $subscriberMap = [
        self::FORMAT_PROGRESS => [
            '\Psecio\Parse\Subscriber\Console\Progress',
            '\Psecio\Parse\Subscriber\Console\Report'
        ],
        self::FORMAT_DOTS => [
            '\Psecio\Parse\Subscriber\Console\Dots',
            '\Psecio\Parse\Subscriber\Console\Report'
        ],
        self::FORMAT_LINES => [
            '\Psecio\Parse\Subscriber\Console\Lines',
            '\Psecio\Parse\Subscriber\Console\Report'
        ],
        self::FORMAT_DEBUG => [
            '\Psecio\Parse\Subscriber\Console\Debug',
            '\Psecio\Parse\Subscriber\Console\Report'
        ],
        self::FORMAT_XML => [
            '\Psecio\Parse\Subscriber\Xml'
        ]
    ];

    /**
     * @var string Format identifier
     */
    private $format;

    /**
     * @var OutputInterface Output object
     */
    private $output;

    /**
     * Set requested format and output object
     *
     * @param  string           $format Requested format
     * @param  OutputInterface  $output Output object
     * @throws RuntimeException If format is not valid
     */
    public function __construct($format, OutputInterface $output)
    {
        if (!isset($this->formatTransitions[$format])) {
            throw new RuntimeException("Unknown output format '{$format}'");
        }

        if ($output->isVeryVerbose()) {
            $format = $this->formatTransitions[$format][self::VERBOSITY_DEBUG];
        } elseif ($output->isVerbose()) {
            $format = $this->formatTransitions[$format][self::VERBOSITY_VERBOSE];
        }

        if (self::FORMAT_PROGRESS == $format && !$output->isDecorated()) {
            $format = self::FORMAT_DOTS;
        }

        $this->format = $format;
        $this->output = $output;
    }

    /**
     * Get format identifier
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Add subscribers to event dispatcher
     *
     * @param  EventDispatcherInterface $dispatcher
     * @return void
     */
    public function addSubscribersTo(EventDispatcherInterface $dispatcher)
    {
        foreach ($this->subscriberMap[$this->getFormat()] as $classname) {
            $dispatcher->addSubscriber(new $classname($this->output));
        }
    }
}
