<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

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

    public function evaluate(Node $node, File $file)
    {
        return !$this->isExpression($node, 'Eval');
    }
}
