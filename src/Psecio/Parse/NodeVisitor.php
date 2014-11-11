<?php

namespace Psecio\Parse;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
	private $tests = array();
	private $results = array();

	public function __construct(\Psecio\Parse\TestCollection $tests)
	{
		$this->tests = $tests;
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
			if ($test->evaluate($node) == true) {
				$this->addResult($node);
			}
		}
	}
}