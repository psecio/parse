<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;

/**
 * Avoid using AND, OR and XOR (in favor of || and &&) as they may cause subtle precedence bugs
 *
 * The logical operators AND, OR and XOR should be avoided as they have lower precedence the assignment operator
 *
 * @todo Add long description to docblock
 */
class LogicalOperators implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        if ($node instanceof LogicalAnd || $node instanceof LogicalOr || $node instanceof LogicalXor) {
            return false;
        }
        return true;
    }
}
