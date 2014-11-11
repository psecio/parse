<?php

namespace Psecio\Parse\Tests;

/**
 * Ensure that header() calls don't use concatenation directly
 */
class TestSetHeaderWithInput extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'header') {
			if ($node->args[0]->value instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
				return false;
			}
		}
		return true;
	}
}