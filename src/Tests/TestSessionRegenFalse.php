<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use session_regenerate_id without setting the param to true
 */
class TestSessionRegenFalse implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait, Helper\IsBoolLiteralTrait;

    public function getDescription()
    {
        return 'If session_regenerate_id is used, must use second paramater and set to true.';
    }

    public function isValid(Node $node)
    {
        if ($this->isFunction($node, 'session_regenerate_id') === true) {
            // If the argument isn't even set...
            if (count($node->args) < 1) {
                return false;
            }

            // If it's set but it's set to false
            $arg = $node->args[0];
            if (!$this->isBoolLiteral($arg->value, true)) {
                return false;
            }
        }

        return true;
    }
}
