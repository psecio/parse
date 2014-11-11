<?php

namespace Psecio\Parse\Tests;

class TestExtractNotOverwrite extends \Psecio\Parse\Test
{
	public function evaluate($node)
	{
		if (get_class($node) == "PhpParser\\Node\\Expr\\FuncCall" && $node->name == 'extract') {
			echo $node->name."\n";
		}
	}
}