<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use 'mb_parse_str' or 'parse_str' as they sets variables into the current scope
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class ParseStr implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if (!$this->isFunctionCall($node, ['parse_str', 'mb_parse_str'])) {
            return true;
        }

        return $this->countCalledFunctionArguments($node) > 1;
    }
}
