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
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionTrait;

    public function isValid(Node $node)
    {
        return !$this->isFunction($node, 'mb_parse_str') && !$this->isFunction($node, 'parse_str');
    }
}
