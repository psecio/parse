<?php

namespace Psecio\Parse\Tests;

/**
 * If we're evaluating against a boolean (true|false)
 * 	ensure we're using type checking, ===
 */
class TestUseTypeCheckEqualsOnBoolean extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\BinaryOp\Equal) {
			// Check to see if either the "right" or "left" are booleans
			$rightName = strtolower((string)$node->right->name);

			if ($rightName == 'true' || $rightName == 'false') {
				$attrs = $node->getAttributes();
				$lines = $file->getLines($attrs['startLine']);
				if (strstr($lines[0], '===') === false) {
					return false
				}
			}
		}
		return true;
	}
}