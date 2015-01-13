<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

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

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == '_REQUEST');
    }
}
