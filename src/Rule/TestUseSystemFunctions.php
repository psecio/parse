<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Using system functions is risky...
 */
class TestUseSystemFunctions implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait, Helper\IsExpressionTrait;

    private static $functions = ['exec', 'passthru', 'system'];

    public function getDescription()
    {
        return 'Use of system functions, especially with user input, is not recommended.';
    }

    public function isValid(Node $node)
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
