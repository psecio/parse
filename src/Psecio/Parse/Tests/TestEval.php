<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use eval. Ever.
 */
class TestEval extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		$result = (get_class($node) !== "PhpParser\\Node\\Expr\\Eval_");
		return $result;
	}
}