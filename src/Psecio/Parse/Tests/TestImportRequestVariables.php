<?php

namespace Psecio\Parse\Tests;

/**
 * Dont use import_request_variables as it puts them into the global scope
 * This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 */
class TestImportRequestVariables extends \Psecio\Parse\Test
{
	protected $description = 'Avoid use of import_request_variables, deprecated in 5.3.0 and removed in 5.4.0';

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction('import_request_variables') === true) {
			return false;
		}
		return true;
	}
}