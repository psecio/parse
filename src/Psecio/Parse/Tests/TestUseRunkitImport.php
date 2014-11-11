<?php

namespace Psecio\Parse\Tests;

/**
 * Using runkit_import overwrites values by default - do not use
 */
class TestUseRunkitImport extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'runkit_import') {
			return false;
		}
		return true;
	}
}