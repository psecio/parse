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
    private $test;
    public $result;

    public function __construct(TestInterface $test)
    {
        $this->test = $test;
        $this->result = true;
    }

    public function enterNode(Node $node)
    {
        $file = m::mock('\Psecio\Parse\File');
        if ($this->test->evaluate($node, $file) === false) {
            $this->result = false;
        }
    }
}
