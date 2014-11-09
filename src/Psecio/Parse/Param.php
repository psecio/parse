<?php

namespace Psecio\Parse;

abstract class Param
{
	private $config;
	private $node;

	public function __construct(array $config, $node)
	{
		$this->setConfig($config);
		$this->setNode($node);
	}

	public function setConfig(array $config)
	{
		$this->config = $config;
	}
	public function getConfig()
	{
		return $this->config;
	}
	public function setNode($node)
	{
		$this->node = $node;
	}
	public function getNode()
	{
		return $this->node;
	}

	abstract public function evaluate();
}