<?php

namespace Psecio\Parse\Tests;

/**
 * The ereg functions have been deprecated as of PHP 5.3.0
 * Don't use them!
 */
class TestNoEregFunctions extends \Psecio\Parse\Test
{
	private $functions = array(
		'ereg', 'eregi', 'ereg_replace', 'eregi_replace'
	);

	public function evaluate($node, $file = null)
	{
		if (get_class($node) == "PhpParser\\Node\\Expr\\FuncCall" && in_array(strtolower($node->name), $this->functions)) {
			return false;
		}
		return true;
	}
}