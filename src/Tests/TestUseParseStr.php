<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Don't use mb_parse_str and parse_str as they set the variables
 * into the current scope
 */
class TestUseParseStr implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'The `parse_str` handling sets (and overwrites) variables in the local scope.';
    }

    public function evaluate(Node $node, File $file)
    {
        return !$this->isFunction($node, 'mb_parse_str') && !$this->isFunction($node, 'parse_str');
    }
}
