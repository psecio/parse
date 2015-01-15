<?php

namespace Psecio\Parse\Event;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Event containing a Rule and a Node object
 */
class IssueEvent extends FileEvent
{
    /**
     * @var RuleInterface The rule object this event conserns
     */
    private $rule;

    /**
     * @var Node The Node object this event conserns
     */
    private $node;

    /**
     * Set Rule and Node objects
     *
     * @param RuleInterface $rule
     * @param Node $node
     * @param File $file
     */
    public function __construct(RuleInterface $rule, Node $node, File $file)
    {
        parent::__construct($file);
        $this->rule = $rule;
        $this->node = $node;
    }

    /**
     * Get rule object ths event conserns
     *
     * @return RuleInterface
     */
    public function getRule()
    {
        return $this->rule;
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
