<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use the mysql_real_escape_string function
 * 	- use bound params/prepared statements instead
 */
class TestUseMysqlRealEscapeString extends \Psecio\Parse\Test
{
	protected $description = 'Use of mysql_real_escape_string is not recommended. Use prepared statements/bind variables.';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();

		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'mysql_real_escape_string') {
			return false;
		}
		return true;
	}
}