<?php

namespace Psecio\Parse\Rule\Helper;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_ as SString;
use LogicException;

/**
 * Helper to check if node is a function call
 */
trait IsFunctionCallTrait
{
    /**
     * Check if $node is a function call
     *
     * Check for name too if provided
     *
     * @param  Node $node
     * @param  string|string[] $names One or more function names to search for
     * @return boolean
     */
    protected function isFunctionCall(Node $node, $names = '')
    {
        if ($node instanceof FuncCall) {
            if ($names) {
                $names = array_map('strtolower', (array)$names);
                return in_array(strtolower($this->getCalledFunctionName($node)), $names, true);
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
        return new Arg(new SString(''));
    }

    /**
     * Count the arguments of called function
     *
     * @param  Node $node
     * @return integer
     * @throws LogicException If node is not an instance of FuncCall
     */
    protected function countCalledFunctionArguments(Node $node)
    {
        if (!$node instanceof FuncCall) {
            throw new LogicException('Node must be an instance of FuncCall, found: ' . get_class($node));
        }
        if (is_array($node->args)) {
            return count($node->args);
        }
        return 0;
    }
}
