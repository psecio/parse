<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

/**
 * Don't use readfile, readlink or readgzfile - they output content directly
 */
class TestUseReadfile implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'The readfile/readlink/readgzfile functions output content directly (possible injection)';
    }

    public function isValid(Node $node)
    {
        return !$this->isFunction($node, 'readfile')
            && !$this->isFunction($node, 'readlink')
            && !$this->isFunction($node, 'readgzfile');
    }
}
