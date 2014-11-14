<?php

namespace Psecio\Parse\Tests;

/**
 * Using runkit_import overwrites values by default - do not use
 */
class TestUseRunkitImport extends \Psecio\Parse\Test
{
	protected $description = 'Using the `runkit_import` function overwrites functions/classes by default.';

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction('runkit_import') === true) {
			return false;
		}
		return true;
	}
}