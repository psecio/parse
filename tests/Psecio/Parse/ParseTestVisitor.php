<?php

namespace tests\Psecio\Parse;

use \Psecio\Parse\Test;
use \Psecio\Parse\Node;

/**
 * Visitor to run a single test against nodes, accumulating results into a single bool
 */
class ParseTestVisitor extends \PhpParser\NodeVisitorAbstract
{
    private $test;
    public $result;

    public function __construct(Test $test)
    {
        $this->test = $test;
        $this->result = true;
    }

    public function enterNode(\PhpParser\Node $node)
    {
        if ($this->test->evaluate(new Node($node), null) === false) {
            $this->result = false;
        }
    }
}
