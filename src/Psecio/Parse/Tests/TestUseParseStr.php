<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use mb_parse_str and parse_str as they set the variables
 * into the current scope
 */
class TestUseParseStr extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'mb_parse_str') {
			return false;
		}
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'parse_str') {
			return false;
		}
		return true;
	}
}