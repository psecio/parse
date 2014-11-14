<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use eval. Ever.
 */
class TestEval extends \Psecio\Parse\Test
{
	protected $description = "Don't use eval. Ever.";

	public function evaluate($node, $file = null)
	{
		return !$node->isExpression('Eval');
	}
}