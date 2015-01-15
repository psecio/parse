<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use readfile, readlink or readgzfile - they output content directly
 */
class Readfile implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'The readfile/readlink/readgzfile functions output content directly (possible injection)';
    }

    /**
     * @todo
     */
    public function getLongDescription()
    {
        return 'TODO';
    }

    public function isValid(Node $node)
    {
        return !$this->isFunction($node, 'readfile')
            && !$this->isFunction($node, 'readlink')
            && !$this->isFunction($node, 'readgzfile');
    }
}
