<?php

namespace Psecio\Parse;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
	private $tests = array();
	private $results = array();
	private $logger;

	public function __construct(\Psecio\Parse\TestCollection $tests, $logger)
	{
		$this->tests = $tests;
		$this->logger = $logger;
	}

	public function getResults()
	{
		return $this->results;
	}

	public function addResult($node)
	{
		$this->results[] = $node;
	}

	public function enterNode(\PhpParser\Node $node)
	{
		foreach ($this->tests as $test) {
			if ($test->evaluate($node) === false) {
				$this->logger->addInfo('oops');

				$this->addResult($node);
			}
		}
	}
}