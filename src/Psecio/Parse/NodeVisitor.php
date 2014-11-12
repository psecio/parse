<?php

namespace Psecio\Parse;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
	/**
	 * Current set of tests to execute
	 * @var array
	 */
	private $tests = array();

	/**
	 * Results from test evaluation
	 * @var array
	 */
	private $results = array();

	/**
	 * Logger object instance (Monolog)
	 * @var object
	 */
	private $logger;

	/**
	 * Current file under evaluation
	 * @var \Psecio\Parse\File
	 */
	private $file;

	/**
	 * Init the object and set up the tests, file and logger
	 *
	 * @param \Psecio\Parse\TestCollection $tests Set of tests in a collection
	 * @param \Psecio\Parse\File $file File object instance
	 * @param object $logger Logger object (Monolog)
	 */
	public function __construct(\Psecio\Parse\TestCollection $tests, \Psecio\Parse\File $file, $logger)
	{
		$this->tests = $tests;
		$this->logger = $logger;
		$this->file = $file;
	}

	/**
	 * Get the current results of test evaluation
	 *
	 * @return array Results set
	 */
	public function getResults()
	{
		return $this->results;
	}

	/**
	 * Add a new test result
	 *
	 * @param object $node Node object
	 */
	public function addResult($node)
	{
		$this->results[] = $node;
	}

	/**
	 * Interface function called when node it first hit
	 *
	 * @param \PhpParser\Node $node Node instance
	 */
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