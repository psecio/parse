<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use $_REQUEST, know where your data is coming from
 */
class TestAvoidRequestUse extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\Variable && $node->name == '_REQUEST') {
			return false;
		}
		return true;
	}
}