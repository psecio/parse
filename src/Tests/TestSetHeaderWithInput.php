<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Ensure that header() calls don't use concatenation directly
 */
class TestSetHeaderWithInput implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'Avoid the use of input in calls to `header`';
    }

    public function evaluate(Node $node, File $file)
    {
        if ($this->isFunction($node, 'header') === true) {
            if ($node->args[0]->value instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
                return false;
            }
        }
        return true;
    }
}
