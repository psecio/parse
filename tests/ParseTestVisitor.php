<?php

namespace Psecio\Parse;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
Use Mockery as m;

/**
 * Visitor to run a single test against nodes, accumulating results into a single bool
 */
class ParseTestVisitor extends \PhpParser\NodeVisitorAbstract
{
    /** @var TestInterface $test  The test to run */
    private $test;

    /**
     * Test result accumulator
     *
     * If any test result is false, $result is false. Essentially it ands all
     * all results together.
     *
     * @var bool $result
     */
    public $result;

    /**
     * Create a vsitor
     *
     * Initializes {@see $result} to be true.
     *
     * @param TestInterface $test  The test to run
     */
    public function __construct(TestInterface $test)
    {
        $this->test = $test;
        $this->result = true;
    }

    /**
     * Evaluate a node
     *
     * @param Node $node  The node to evaluate
     */
    public function enterNode(Node $node)
    {
        $file = m::mock('\Psecio\Parse\File');
        if ($this->test->evaluate($node, $file) === false) {
            $this->result = false;
        }
    }
}
