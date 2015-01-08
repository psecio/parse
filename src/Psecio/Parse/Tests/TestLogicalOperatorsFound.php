<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;

/**
 * The logical operators OR and AND should be avoided as they have lower precedence than || and &&
 */
class TestLogicalOperatorsFound implements TestInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return "Avoid the use of OR and AND in favor of || and && as they may cause subtle bugs due to precedence";
    }

    public function evaluate(Node $node, File $file)
    {
        if ($node instanceof LogicalAnd || $node instanceof LogicalOr) {
            return false;
        }
        return true;
    }
}
