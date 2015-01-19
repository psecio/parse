<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Avoid the use of $_REQUEST (know where your data comes from)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class RequestUse implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == '_REQUEST');
    }
}
