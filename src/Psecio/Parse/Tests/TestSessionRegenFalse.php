<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use session_regenerate_id without setting the param to true
 */
class TestSessionRegenFalse extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'session_regenerate_id') {
			// If the argument isn't even set...
			if (count($node->args) < 1) {
				return false;
			}

			// If it's set but it's set to false
			$arg = $node->args[0];
			if (strtolower($arg->value->name->parts[0]) !== 'true') {
				return false;
			}
		}
		return true;
	}
}