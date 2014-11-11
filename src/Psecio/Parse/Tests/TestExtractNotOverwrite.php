<?php

namespace Psecio\Parse\Tests;

/**
 * For the extract function, if either:
 * 	- the second param is not set (overwrite by default)
 *  = the second param is set but is EXTR_OVERWRITE
 * fail...
 */
class TestExtractNotOverwrite extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if (get_class($node) == "PhpParser\\Node\\Expr\\FuncCall" && $node->name == 'extract') {

			// Check to be sure it has two arguments
			if (count($node->args) < 2) {
				return false;
			}

			// So we have two parameters...see if #2 is not equal to EXTR_OVERWRITE
			if ($node->args[1]->value->name->parts[0] === 'EXTR_OVERWRITE') {
				return false;
			}
		}
		return true;
	}
}