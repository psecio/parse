<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;

/**
 * Evaluation with booleans should use strict type checking (ex: if $foo === false)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class BooleanIdentity implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsBoolLiteralTrait;

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
