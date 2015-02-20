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
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait, Helper\IsBoolLiteralTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, 'session_regenerate_id')) {
            return $this->isBoolLiteral($this->getCalledFunctionArgument($node, 0)->value, true);
        }
        return true;
    }
}
