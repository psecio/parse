<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * The "display_errors" setting should not be enabled manually
 */
class TestDisableDisplayErrors implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'The "display_errors" setting should not be enabled manually';
    }

    public function evaluate(Node $node, File $file)
    {
        if ($this->isFunction($node, 'ini_set') === true) {
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
