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
			if ($this->isBoolLiteral($node->left) || $this->isBoolLiteral($node->right)) {
				$attrs = $node->getAttributes();
				$lines = $file->getLines($attrs['startLine']);
				if (strstr($lines[0], '===') === false) {
					return false;
				}
			}
		}
		return true;
	}

	private function isBoolLiteral($node)
	{
		if ($node->name instanceof \PhpParser\Node\Name) {
			$name = strtolower($node->name);
			if ($name == 'true' || $name == 'false') {
				return true;
			}
		}
		return false;
	}
}
