<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Avoid the use of 'http_raw_post_data', deprecated and will be removed
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class HttpRawPostData implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'http_raw_post_data');
    }
}
