<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * 'header()' calls should not use concatenation directly
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class SetHeaderWithInput implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunction($node, 'header') === true) {
            if ($node->args[0]->value instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
                return false;
            }
        }
        return true;
    }
}
