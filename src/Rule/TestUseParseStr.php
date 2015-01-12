<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use mb_parse_str and parse_str as they set the variables
 * into the current scope
 */
class TestUseParseStr implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'The `parse_str` handling sets (and overwrites) variables in the local scope.';
    }

    public function isValid(Node $node)
    {
        return !$this->isFunction($node, 'mb_parse_str') && !$this->isFunction($node, 'parse_str');
    }
}
