<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Evaluation using in_array should enforce type checking (third parameter should be true)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class InArrayStrict implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionCallTrait, Helper\IsBoolLiteralTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, 'in_array') === true) {

            // Be sure there's three params
            if (count($node->args) < 3) {
                return false;
            }

            // Be sure it's a boolean value
            if (!$this->isBoolLiteral($node->args[2]->value)) {
                return false;
            }

            // Be sure the value is "true"
            if ((string)$node->args[2]->value->name !== 'true') {
                return false;
            }

            return true;
        }
        
        return true;
    }
}
