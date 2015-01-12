<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Ensure that the regular expression handling doesn't use the /e modifier
 */
class TestUseExecModifierInRegex implements RuleInterface
{
    use Helper\NameTrait;

    private static $functions = ['preg_match', 'preg_match_all'];

    public function getDescription()
    {
        return 'Do not use the eval modifier in regular expressions (\e)';
    }

    public function isValid(Node $node)
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
