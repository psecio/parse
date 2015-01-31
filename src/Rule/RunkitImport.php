<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Using the 'runkit_import' function overwrites functions/classes by default
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class RunkitImport implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        return !$this->isFunctionCall($node, 'runkit_import');
    }
}
