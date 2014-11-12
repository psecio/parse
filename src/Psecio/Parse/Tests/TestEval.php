<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use eval. Ever.
 */
class TestEval extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		return !$node->isExpression('Eval');
	}
}