<?php

namespace Psecio\Parse\Tests;

/**
 * Using system functions is risky...
 */
class TestUseSystemFunctions extends \Psecio\Parse\Test
{
	private $functions = array(
		'exec', 'passthru', 'system', 'exec'
	);

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction() === true) {
			foreach ($this->functions as $function) {
				if (strtolower($node->name) == $function) {
					return false;
				}
			}
		}

		if ($node->isExpression('ShellExec') === true) {
			return false;
		}

		return true;
	}
}