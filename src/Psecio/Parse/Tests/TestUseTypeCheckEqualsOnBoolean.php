<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * If we're evaluating against a boolean (true|false)
 * ensure we're using type checking, ===
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
        if ($node instanceof \PhpParser\Node\Expr\BinaryOp\Equal) {
            // Check to see if either the "right" or "left" are booleans
            if ($this->isBoolLiteral($node->left) || $this->isBoolLiteral($node->right)) {
                $attrs = $node->getAttributes();
                $lines = $file->getLines($attrs['startLine']);
                if (strstr($lines[0], '===') === false) {
                    return false;
                }
            }
        }
        return true;
    }
}
