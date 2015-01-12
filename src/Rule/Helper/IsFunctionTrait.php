<?php

namespace Psecio\Parse\Rule\Helper;

use \PhpParser\Node;

/**
 * Helper to evaluate if node is a function
 */
trait IsFunctionTrait
{
    /**
     * Evaluate if $node is a function instance
     *
     * Check for name too if provided
     *
     * @param  Node $node
     * @param  string $name Function name
     * @return boolean
     */
    protected function isFunction(Node $node, $name = null)
    {
        $result = false;
        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            $result = true;
        }
        if ($result === true && $name !== null) {
            // This matches variables used as object names
            if ($node->name instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
                return $result;
            }
            $nodeName = ($node->name instanceof \PhpParser\Node\Expr\Variable)
                ? $node->name->name : (string)$node->name;

            if ($nodeName !== $name) {
                $result = false;
            }
        }
        return $result;
    }
}
