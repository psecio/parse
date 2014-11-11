<?php

namespace Psecio\Parse;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
	private $tests = array();
	private $results = array();
	private $logger;
	private $file;

	public function __construct(\Psecio\Parse\TestCollection $tests, \Psecio\Parse\File $file, $logger)
	{
		$this->tests = $tests;
		$this->logger = $logger;
		$this->file = $file;
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
			if ($test->evaluate($node, $this->file) === false) {
				$this->addResult(array(
					'test' => $test,
					'node' => $node
				));
			}
		}
	}
}