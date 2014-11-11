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

	public function evaluate($node, $file = null)
	{
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