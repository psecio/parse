<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Don't use http_raw_post_data
 */
class TestHttpRawPostData implements TestInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Avoid the use of http_raw_post_data. Deprecated and will be removed.';
    }

    public function evaluate(Node $node, File $file)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'http_raw_post_data');
    }
}
