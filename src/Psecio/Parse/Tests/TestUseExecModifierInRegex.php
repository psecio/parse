<?php

namespace Psecio\Parse\Tests;

/**
 * Ensure that the regular expression handling doesn't use the /e modifier
 */
class TestUseExecModifierInRegex extends \Psecio\Parse\Test
{
	private $functions = array(
		'preg_match', 'preg_match_all'
	);

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();

		if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array(strtolower($node->name), $this->functions)) {
			$regex = (string)$node->args[0]->value->value;
			if (strstr($regex, '/e') !== false) {
				return false;
			}
		}
		return true;
	}
}