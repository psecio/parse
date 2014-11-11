<?php

namespace Psecio\Parse\Tests;

class TestEval extends \Psecio\Parse\Test
{
	public function evaluate($node)
	{
		return (get_class($node) == "PhpParser\\Node\\Expr\\Eval_");
	}
}