<?php

namespace Psecio\Parse\Tests;

/**
 * The "display_errors" setting should not be enabled manually
 */
class TestDisableDisplayErrors extends \Psecio\Parse\Test
{
    protected $description = 'The "display_errors" setting should not be enabled manually';

    public function evaluate($node, $file = null)
    {
        if ($node->isFunction('ini_set') === true) {
            // see if the setting is "display_errors" && if they're enabling it
            if ($node->args[0]->value->value == 'display_errors') {
                $value = $node->args[1]->value;

                if ($value instanceof \PhpParser\Node\Expr\ConstFetch) {
                    $value = $value->name->parts[0];
                } else {
                    $value = $node->args[1]->value->value;
                }

                if ($value == 1 || $value == true) {
                    return false;
                }
            }
        }
        return true;
    }
}