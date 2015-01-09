<?php

namespace Psecio\Parse\Event;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Event containing a Test and a Node object
 */
class IssueEvent extends FileEvent
{
    /**
     * @var TestInterface The Test object this event conserns
     */
    private $test;

    /**
     * @var Node The Node object this event conserns
     */
    private $node;

    /**
     * Set Test and Node objects
     *
     * @param TestInterface $test
     * @param Node $node
     * @param File $file
     */
    public function __construct(TestInterface $test, Node $node, File $file)
    {
        parent::__construct($file);
        $this->test = $test;
        $this->node = $node;
    }

    /**
     * Get Test object ths event conserns
     *
     * @return TestInterface
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Get Node object ths event conserns
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }
}
