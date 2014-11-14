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

	protected $description = 'Use of system functions, especially with user input, is not recommended.';

	public function evaluate($node, $file = null)
	{
		if ($node->isFunction() === true) {
			foreach ($this->functions as $function) {
				$name = (is_object($node->name)) ? $node->name->parts[0] : $node->name;

				if (strtolower($name) == $function) {
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