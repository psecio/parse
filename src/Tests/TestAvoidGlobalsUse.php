<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Don't use $GLOBALS, know where your data is coming from
 */
class TestAvoidGlobalsUse implements TestInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'The use of $GLOBALS should be avoided.';
    }

    public function evaluate(Node $node, File $file)
    {
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'GLOBALS');
    }
}
