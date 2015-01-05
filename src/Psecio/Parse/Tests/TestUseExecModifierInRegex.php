<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Ensure that the regular expression handling doesn't use the /e modifier
 */
class TestUseExecModifierInRegex implements TestInterface
{
    use Helper\NameTrait;

    private static $functions = ['preg_match', 'preg_match_all'];

    public function getDescription()
    {
        return 'Do not use the eval modifier in regular expressions (\e)';
    }

    public function evaluate(Node $node, File $file)
    {
        $nodeName = (is_object($node->name)) ? $node->name->parts[0] : $node->name;

        if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array(strtolower($nodeName), self::$functions)) {
            $regex = (string)$node->args[0]->value->value;
            if (strstr($regex, '/e') !== false) {
                return false;
            }
        }

        return true;
    }
}
