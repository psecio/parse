<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Avoid hard-coding sensitive values (ex. "username", "password", etc.)
 */
class TestAvoidHardcodedSensitiveValues implements TestInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    private static $sensitiveNames = ['username', 'password', 'user', 'pass', 'pwd'];

    public function getDescription()
    {
        return 'Avoid hard-coding sensitive values (ex. "username", "password", etc.)';
    }

    public function evaluate(Node $node, File $file)
    {
        // Fail on straight $var = 'value', where $var is in $sensitiveNames

        if (!$this->isExpression($node, 'Assign')) {
            // Not an assignment
            return true;
        }

        if (!in_array(strtolower($node->var->name), self::$sensitiveNames)) {
            // Not in our list
            return true;
        }

        // Fail if assigning to a scalar, succeed otherwise
        return !($node->expr instanceof \PhpParser\Node\Scalar\String);
    }
}
