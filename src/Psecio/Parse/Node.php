<?php

namespace Psecio\Parse;

class Node
{
	/**
	 * Curent node instance
	 * @var \PhpParser\Node
	 */
	private $node;

	/**
	 * Init the object and set the node instance
	 *
	 * @param \PhpParser\Node $node Node instance
	 */
	public function __construct(\PhpParser\Node $node)
	{
		$this->setNode($node);
	}

	/**
	 * Set the current node object
	 *
	 * @param \PhpParser\Node $node Node instance
	 */
	public function setNode(\PhpParser\Node $node)
	{
		$this->node = $node;
	}

	/**
	 * Get the current node instance
	 *
	 * @return \PhpParser\Node object
	 */
	public function getNode()
	{
		return $this->node;
	}

	/**
	 * Pass along a getter on the node object itself
	 *
	 * @param string $name Property name
	 * @return mixed Requested data
	 */
	public function __get($name)
	{
		return $this->getNode()->$name;
	}

	/**
	 * Evaluate if the current node is a function instance
	 * 	check for name too if provided
	 *
	 * @param string $name Function name [optional]
	 * @return boolean Is/is not function (and/or name match)
	 */
	public function isFunction($name = null)
	{
		$result = false;
		if ($this->node instanceof \PhpParser\Node\Expr\FuncCall) {
			$result = true;
		}
		if ($name !== null) {
			if ((string)$this->node->name !== $name) {
				$result = false;
			}
		}
		return $result;
	}

	/**
	 * Check to see if the current node is an expression
	 *
	 * @param string $name Expression type
	 * @return boolean Is/is not an expression match
	 */
	public function isExpression($name)
	{
		$result = false;
		$underNS = 'PhpParser\\Node\\Expr\\'.$name.'_';
		$normalNS = 'PhpParser\\Node\\Expr\\'.$name;

		if ($this->node instanceof $underNS || $this->node instanceof $normalNS) {
			$result = true;
		}
		return $result;
	}
}