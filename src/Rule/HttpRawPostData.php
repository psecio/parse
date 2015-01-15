<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use http_raw_post_data
 */
class HttpRawPostData implements RuleInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Avoid the use of http_raw_post_data. Deprecated and will be removed.';
    }

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'http_raw_post_data');
    }
}
