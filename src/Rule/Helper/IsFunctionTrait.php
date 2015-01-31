<?php

namespace Psecio\Parse\Rule\Helper;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String;
use LogicException;

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
     * @param  Node   $node
     * @param  string $name Function name
     * @return boolean
     */
    protected function isFunction(Node $node, $name = '')
    {
        if ($node instanceof FuncCall) {
            if ($name) {
                return $this->getCalledFunctionName($node) === $name;
            }
            return true;
        }
        return false;
    }

    /**
     * Get name of called function
     *
     * @param  Node   $node
     * @return string Empty string if name could not be parsed
     * @throws LogicException If node is not an instance of FuncCall
     */
    protected function getCalledFunctionName(Node $node)
    {
        if (!$node instanceof FuncCall) {
            throw new LogicException('Node must be an instance of FuncCall, found: ' . get_class($node));
        }
        if ($node->name instanceof Name) {
            return (string)$node->name;
        }
        return '';
    }

    /**
     * Get argument of called function
     *
     * @param  Node    $node
     * @param  integer $index Index of argument to fetch
     * @return Arg     If argument is not found an empty string is returned
     * @throws LogicException If node is not an instance of FuncCall
     */
    protected function getCalledFunctionArgument(Node $node, $index)
    {
        if (!$node instanceof FuncCall) {
            throw new LogicException('Node must be an instance of FuncCall, found: ' . get_class($node));
        }
        if (is_array($node->args) && array_key_exists($index, $node->args)) {
            return $node->args[$index];
        }
        return new Arg(new String(''));
    }
}
