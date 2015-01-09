<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Using runkit_import overwrites values by default - do not use
 */
class TestUseRunkitImport implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'Using the `runkit_import` function overwrites functions/classes by default.';
    }

    public function evaluate(Node $node, File $file)
    {
        return !$this->isFunction($node, 'runkit_import');
    }
}
