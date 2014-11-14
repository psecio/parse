<?php

namespace Psecio\Parse\Tests;

/**
 * Logical operators should be avoided
 */
class TestLogicalOperatorsFound extends \Psecio\Parse\Test
{
	private $operators = array(
		'and',
		'or',
		'xor'
	);

	protected $description = 'Avoid the use of logical operations (XOR, OR, etc) in favor of operators like && and ||';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();
		if ($node instanceof \PhpParser\Node\Expr\BinaryOp\NotIdentical) {
			// See what's on the line
			$attr = $node->getAttributes();
			$lines = $file->getLines($attr['startLine']);

			foreach ($lines as $line) {
				foreach ($this->operators as $operator) {
					if (stristr($line, $operator) !== false) {
						return false;
					}
				}
			}
		}
		return true;
	}
}