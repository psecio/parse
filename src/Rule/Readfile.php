<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * The readfile/readlink/readgzfile functions output content directly (possible injection)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class Readfile implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        return !$this->isFunctionCall($node, ['readfile', 'readlink', 'readgzfile']);
    }
}
