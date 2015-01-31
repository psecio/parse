<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * By default 'extract' overwrites variables in the local scope
 *
 * For the extract function, if either:
 *  - the second param is not set (overwrite by default)
 *  = the second param is set but is EXTR_OVERWRITE
 * fail...
 *
 * @todo Add long description to docblock
 */
class Extract implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, 'extract')) {
            // Check to be sure it has two arguments
            if ($this->countCalledFunctionArguments($node) < 2) {
                return false;
            }

            $arg = $this->getCalledFunctionArgument($node, 1);

            $name = (isset($arg->value->name->parts[0]))
                ? $arg->value->name->parts[0]
                : $arg->value->name;

            // So we have two parameters...see if #2 is not equal to EXTR_OVERWRITE
            return $name !== 'EXTR_OVERWRITE';
        }
        return true;
    }
}
