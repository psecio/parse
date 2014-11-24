<?php

namespace Psecio\Parse\Tests;

/**
 * Avoid hard-coding sensitive values (ex. "username", "password", etc.)
 */
class TestAvoidHardcodedSensitiveValues extends \Psecio\Parse\Test
{
    protected $sensitiveNames = array(
        'username', 'password', 'user', 'pass'
    );
    protected $description = 'Avoid hard-coding sensitive values (ex. "username", "password", etc.)';

    public function evaluate($node, $file = null)
    {
        if ($node->isExpression('Assign') === true) {
            $node = $node->getNode();
            $varName = $node->var->name;

            // If it's in our list, see if it's just being assigned a value
            if (in_array($node->var->name, $this->sensitiveNames)) {
                if ($node->expr instanceof \PhpParser\Node\Scalar\String) {
                    return false;
                }
            }
        }

        return true;
    }
}