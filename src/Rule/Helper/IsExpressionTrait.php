<?php

namespace Psecio\Parse\Rule\Helper;

use \PhpParser\Node;

/**
 * Helper to evaluate if node is an expression
 */
trait IsExpressionTrait
{
    /**
     * Check to see if $node is an expression
     *
     * @param  Node $node
     * @param  string $name Expression type
     * @return boolean
     */
    protected function isExpression(Node $node, $name)
    {
        $result = false;
        $underNS = 'PhpParser\\Node\\Expr\\'.$name.'_';
        $normalNS = 'PhpParser\\Node\\Expr\\'.$name;

        if ($node instanceof $underNS || $node instanceof $normalNS) {
            $result = true;
        }
        return $result;
    }
}
