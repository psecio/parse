<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Don't use $_REQUEST, know where your data is coming from
 */
class TestAvoidRequestUse implements TestInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Avoid the use of $_REQUEST (know where your data comes fron)';
    }

    public function evaluate(Node $node, File $file)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == '_REQUEST');
    }
}
