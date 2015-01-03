<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Using system functions is risky...
 */
class TestUseSystemFunctions implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait, Helper\IsExpressionTrait;

    private static $functions = ['exec', 'passthru', 'system'];

    public function getDescription()
    {
        return 'Use of system functions, especially with user input, is not recommended.';
    }

    public function evaluate(Node $node, File $file)
    {
        if ($this->isFunction($node)) {
            $name = (is_object($node->name)) ? $node->name->parts[0] : $node->name;
            if (in_array(strtolower($name), self::$functions)) {
                return false;
            }
        }

        return !$this->isExpression($node, 'ShellExec');
    }
}
