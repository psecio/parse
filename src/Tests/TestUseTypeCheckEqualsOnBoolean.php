<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;
use PhpParser\Node\Expr\BinaryOp\Equal;

/**
 * If we're evaluating against a boolean (true|false) ensure we're using type checking (===)
 */
class TestUseTypeCheckEqualsOnBoolean implements TestInterface
{
    use Helper\NameTrait, Helper\IsBoolLiteralTrait;

    public function getDescription()
    {
        return 'Evaluation with booleans should use strict type checking (ex: if $foo === false)';
    }

    public function evaluate(Node $node, File $file)
    {
        if ($node instanceof Equal) {
            if ($this->isBoolLiteral($node->left) || $this->isBoolLiteral($node->right)) {
                return false;
            }
        }
        return true;
    }
}
