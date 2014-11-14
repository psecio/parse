<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use mb_parse_str and parse_str as they set the variables
 * into the current scope
 */
class TestUseParseStr extends \Psecio\Parse\Test
{
	protected $description = 'The `parse_str` handling sets (and overwrites) variables in the local scope.';

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction('mb_parse_str') === true || $node->isFunction('parse_str') === true) {
			return false;
		}
		return true;
	}
}