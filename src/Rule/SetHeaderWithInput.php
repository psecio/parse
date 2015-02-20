<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;

/**
 * 'header()' calls should not use concatenation directly
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class SetHeaderWithInput implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, 'header')) {
            if ($this->getCalledFunctionArgument($node, 0)->value instanceof Concat) {
                return false;
            }
        }
        return true;
    }
}
