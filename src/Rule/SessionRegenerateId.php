<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * If 'session_regenerate_id' is used, must use second paramater and set to true
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class SessionRegenerateId implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionTrait, Helper\IsBoolLiteralTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunction($node, 'session_regenerate_id') === true) {
            // If the argument isn't even set...
            if (count($node->args) < 1) {
                return false;
            }

            // If it's set but it's set to false
            $arg = $node->args[0];
            if (!$this->isBoolLiteral($arg->value, true)) {
                return false;
            }
        }

        return true;
    }
}
