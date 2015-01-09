<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * The ereg functions have been deprecated as of PHP 5.3.0. Don't use them!
 */
class TestNoEregFunctions implements TestInterface
{
    use Helper\NameTrait;

    private static $functions = ['ereg', 'eregi', 'ereg_replace', 'eregi_replace'];

    public function getDescription()
    {
        return 'Remove any use of ereg functions, deprecated and removed. Use preg_*';
    }

    public function evaluate(Node $node, File $file)
    {
        $nodeName = (is_object($node->name)) ? $node->name->parts[0] : $node->name;

        if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array(strtolower($nodeName), self::$functions)) {
            return false;
        }

        return true;
    }
}
