<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

/**
 * Check for output functions that use a variable in the output
 */
class TestOutputWithVariable implements TestInterface
{
    use Helper\NameTrait;

    private static $outputFunctions = ['print_r', 'printf', 'vprintf', 'sprintf'];

    public function getDescription()
    {
        return 'Avoid the use of an output method (echo, print, etc) directly with a variable';
    }

    public function isValid(Node $node)
    {
        // See if our echo or print (constructs) uses concat
        if ($node instanceof \PhpParser\Node\Stmt\Echo_ || $node instanceof PhpParser\Node\Expr\Print_) {
            if (isset($node->exprs[0]) && $node->exprs[0] instanceof PhpParser\Node\Expr\BinaryOp\Concat) {
                return false;
            }
        }

        // See if our other output functions use concat
        if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array($node->name, self::$outputFunctions)) {
            if (isset($node->args[0]) && $node->args[0]->value instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
                return false;
            }
        }

        return true;
    }
}
