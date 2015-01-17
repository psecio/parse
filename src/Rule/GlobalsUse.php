<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * The use of $GLOBALS should be avoided, know where your data is coming from
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class GlobalsUse implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'GLOBALS');
    }
}
