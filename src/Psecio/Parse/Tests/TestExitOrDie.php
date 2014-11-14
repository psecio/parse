<?php

namespace Psecio\Parse\Tests;

/**
 * Exit or die usage should be avoided
 */
class TestExitOrDie extends \Psecio\Parse\Test
{
	protected $description = 'Avoid the use of `exit` or `die` (could lead to injection issues (direct output)';

	public function evaluate($node, $file = null)
	{
		if ($node->isExpression('Exit')) {
			return false;
		}
		return true;
	}
}