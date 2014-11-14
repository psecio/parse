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

	protected $description = 'Do not use the eval modifier in regular expressions (\e)';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();
		$nodeName = (is_object($node->name)) ? $node->name->parts[0] : $node->name;

		if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array(strtolower($nodeName), $this->functions)) {
			$regex = (string)$node->args[0]->value->value;
			if (strstr($regex, '/e') !== false) {
				return false;
			}
		}
		return true;
	}
}