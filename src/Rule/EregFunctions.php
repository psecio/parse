<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Remove any use of ereg functions, deprecated as of PHP 5.3.0. Use preg_*
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
*/
class EregFunctions implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, ['ereg', 'eregi', 'ereg_replace', 'eregi_replace'])) {
            return false;
        }
        return true;
    }
}
