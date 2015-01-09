<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Logical operators should be avoided
 */
class TestLogicalOperatorsFound implements TestInterface
{
    use Helper\NameTrait;

    private static $operators = ['and', 'or', 'xor'];

    public function getDescription()
    {
        return 'Avoid the use of logical operations (XOR, OR, etc) in favor of operators like && and ||';
    }

    public function evaluate(Node $node, File $file)
    {
        if ($node instanceof \PhpParser\Node\Expr\BinaryOp\NotIdentical) {
            // See what's on the line
            $attr = $node->getAttributes();
            $lines = $file->getLines($attr['startLine']);

            foreach ($lines as $line) {
                foreach (self::$operators as $operator) {
                    if (stristr($line, $operator) !== false) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
