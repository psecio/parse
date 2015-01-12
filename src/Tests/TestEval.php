<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

/**
 * Don't use eval. Ever.
 */
class TestEval implements TestInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    public function getDescription()
    {
        return "Don't use eval. Ever.";
    }

    public function isValid(Node $node)
    {
        return !$this->isExpression($node, 'Eval');
    }
}
