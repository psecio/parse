<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

/**
 * Visitor to run a single test against nodes, accumulating results into a single bool
 */
class RuleTestVisitor extends NodeVisitorAbstract
{
    /**
     * @var RuleInterface Rule under test
     */
    private $rule;

    /**
     * Test result accumulator
     *
     * If node is not valid $result is false.
     *
     * @var boolean
     */
    public $result = true;

    /**
     * Create a visitor
     *
     * @param RuleInterface $rule Rule under test
     */
    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * Evaluate a node
     *
     * @param Node $node The node to evaluate
     */
    public function enterNode(Node $node)
    {
        if (!$this->rule->isValid($node)) {
            $this->result = false;
        }
    }
}
