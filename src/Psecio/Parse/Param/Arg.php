<?php

namespace Psecio\Parse\Param;

class Arg extends \Psecio\Parse\Param
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

		foreach ($config as $argument) {
			$location = 1;
			$required = false;

			// See if we have a location or is required
			foreach ($argument['options'] as $option) {
				if ($option['param'] == 'location') {
					$location = $option['value'];
				}
				if ($option['param'] == 'required' && $option['value'] == 'true') {
					$required = true;
				}
			}

			// If it's required and we don't have a param there...
			if ($required === true && count($arguments) < $location) {
				return false;
			}
			$argumentObject = $arguments[$location-1];

			// Now lets run through the evaluations
			$pass = true;
			foreach ($argument['options'] as $option) {
				if ($option['param'] == 'eval') {
					// Cast our variables correctly
					$argumentValue = settype($argumentObject->value->value, $option['type']);
					$compareValue = settype($option['value'], $option['type']);

					switch($option['operation']) {
						case '=':
							if ($argumentValue !== $compareValue && $pass === true) {
								$pass = false;
							}
							break;
					}
				}
			}

		}

		return $pass;
	}
}