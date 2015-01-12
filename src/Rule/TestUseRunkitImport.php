<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Using runkit_import overwrites values by default - do not use
 */
class TestUseRunkitImport implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'Using the `runkit_import` function overwrites functions/classes by default.';
    }

    public function isValid(Node $node)
    {
        return !$this->isFunction($node, 'runkit_import');
    }
}
