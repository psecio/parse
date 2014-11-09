<?php

namespace Psecio\Parse;

abstract class Output
{
	/**
	 * Geenrate the output and return
	 *
	 * @param array $data Scan results data
	 * @return mixed Formatted results
	 */
	abstract public function generate(array $data);
}