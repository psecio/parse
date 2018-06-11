<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\FuncCall;

/**
 * The third parameter should be set (and be true) on in_array to avoid type switching issues
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class TypeSafeInArray implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsBoolLiteralTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($node instanceof FuncCall && $this->getCalledFunctionName($node) === 'in_array') {            
            if (count($node->args) == 2) {
                return false;
            } elseif (count($node->args) == 3 && $this->isBoolLiteral($node->args[2]->value, false)) {
                return false;
            }
        }

        return true;
    }
}
