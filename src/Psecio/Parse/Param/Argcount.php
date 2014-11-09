<?php

namespace Psecio\Parse\Param;

class Argcount extends \Psecio\Parse\Param
{
	/**
	 * Evaluate the argument based on the criteria given
	 *
	 * @return boolean Pass/fail status of all evaluation
	 */
	public function evaluate()
	{
		$config = $this->getConfig();
		$node = $this->getNode();
		$arguments = $node->args;
		$options = $config[0]['options'];

		// Get the value and operation we're looking for
		$value = $options[0]['value'];
		$operation = $options[0]['operation'];

		switch($operation) {
			case '=':
				return count($arguments) == $value;
				break;
			case '<':
				return count($arguments) < $value;
				break;
			case '>':
				return count($arguments) > $value;
				break;
		}
		return false;
	}
}