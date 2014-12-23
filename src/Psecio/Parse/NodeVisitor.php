<?php

namespace Psecio\Parse;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var TestCollection Current set of tests to execute
     */
    private $tests;

    /**
     * @var File Current file under evaluation
     */
    private $file;

    /**
     * @var array Results from test evaluation
     */
    private $results = [];

    /**
     * Init the object and set up the tests and file
     *
     * @param TestCollection $tests Set of tests in a collection
     * @param File $file File object instance
     */
    public function __construct(TestCollection $tests, File $file)
    {
        $this->tests = $tests;
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
        // Make a node object with helpers
        $node = new Node($node);

        foreach ($this->tests as $test) {
            if ($test->evaluate($node, $this->file) === false) {
                $this->addResult(
                	[
	                    'test' => $test,
	                    'node' => $node
                	]
                );
            }
        }
    }
}
