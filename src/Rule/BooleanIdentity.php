<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;

/**
 * If we're evaluating against a boolean (true|false) ensure we're using type checking (===)
 */
class BooleanIdentity implements RuleInterface
{
    use Helper\NameTrait, Helper\IsBoolLiteralTrait;

    public function getDescription()
    {
        return 'Evaluation with booleans should use strict type checking (ex: if $foo === false)';
    }

    public function isValid(Node $node)
    {
        if ($node instanceof Equal) {
            if ($this->isBoolLiteral($node->left) || $this->isBoolLiteral($node->right)) {
                return false;
            }
        }
        return true;
    }
}
