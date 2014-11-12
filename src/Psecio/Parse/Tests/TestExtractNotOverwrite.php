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
		if ($node->isFunction('extract') === true) {

			// Check to be sure it has two arguments
			if (count($node->args) < 2) {
				return false;
			}

			$name = (!isset($node->args[1]->value->name->parts[0]))
				? $node->args[1]->value->name : $node->args[1]->value->name->parts[0];

			// So we have two parameters...see if #2 is not equal to EXTR_OVERWRITE
			if ($name === 'EXTR_OVERWRITE') {
				return false;
			}
		}
		return true;
	}
}