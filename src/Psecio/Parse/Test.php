<?php

namespace Psecio\Parse;

abstract class Test
{
	private $logger;

	public function __construct($logger)
	{
		$this->logger = $logger;
	}
	public function getLogger()
	{
		return $this->logger;
	}
	abstract public function evaluate($node, $file = null);
}