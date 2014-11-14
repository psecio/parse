<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use session_regenerate_id without setting the param to true
 */
class TestSessionRegenFalse extends \Psecio\Parse\Test
{
	protected $description = 'If session_regenerate_id is used, must use second paramater and set to true.';

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction('session_regenerate_id') === true) {
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