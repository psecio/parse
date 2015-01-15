<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Ensure that header() calls don't use concatenation directly
 */
class SetHeaderWithInput implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'Avoid the use of input in calls to `header`';
    }

    /**
     * @todo
     */
    public function getLongDescription()
    {
        return 'TODO';
    }

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
