<?php

namespace Psecio\Parse\Tests;

/**
 * Dont use import_request_variables as it puts them into the global scope
 * This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 */
class TestImportRequestVariables extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'import_request_variables') {
			return false;
		}
		return true;
	}
}