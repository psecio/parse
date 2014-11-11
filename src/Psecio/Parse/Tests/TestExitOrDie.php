<?php

namespace Psecio\Parse\Tests;

/**
 * Exit or die usage should be avoided
 */
class TestExitOrDie extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\Exit_) {
			return false;
		}
		return true;
	}
}