<?php

namespace Psecio\Parse\Tests;

/**
 * If we're evaluating against a boolean (true|false)
 * 	ensure we're using type checking, ===
 */
class TestUseTypeCheckEqualsOnBoolean extends \Psecio\Parse\Test
{
	protected $description = 'Evaluation with booleans should use strict type checking (ex: if $foo === false)';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();

		if ($node instanceof \PhpParser\Node\Expr\BinaryOp\Equal) {
			// Check to see if either the "right" or "left" are booleans
			if ($this->nodeIsBoolLiteral($node->left) || $this->nodeIsBoolLiteral($node->right)) {
				$attrs = $node->getAttributes();
				$lines = $file->getLines($attrs['startLine']);
				if (strstr($lines[0], '===') === false) {
					return false;
				}
			}
		}
		return true;
	}
}
