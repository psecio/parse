<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use readfile, readlink or readgzfile - they output content directly
 */
class TestUseReadfile extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'readfile') {
			return false;
		}
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'readlink') {
			return false;
		}
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'readgzfile') {
			return false;
		}
		return true;
	}
}