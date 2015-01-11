<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

/**
 * Avoid hard-coding sensitive values (ex. "username", "password", etc.)
 */
class TestAvoidHardcodedSensitiveValues implements TestInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    private static $sensitiveNames = ['username', 'password', 'user', 'pass'];

    public function getDescription()
    {
        return 'Avoid hard-coding sensitive values (ex. "username", "password", etc.)';
    }

    public function isValid(Node $node)
    {
        if ($this->isExpression($node, 'Assign') === true) {
            // If it's in our list, see if it's just being assigned a value
            if (in_array($node->var->name, self::$sensitiveNames)) {
                if ($node->expr instanceof \PhpParser\Node\Scalar\String) {
                    return false;
                }
            }
        }

        return true;
    }
}
