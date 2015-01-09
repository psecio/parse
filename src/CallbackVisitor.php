<?php

namespace Psecio\Parse;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

/**
 * Evaluate tests and call callback on test failure
 */
class CallbackVisitor extends NodeVisitorAbstract
{
    /**
     * @var TestCollection Tests to execute
     */
    private $testCollection;

    /**
     * @var callable Test fail callback
     */
    private $callback;

    /**
     * @var File Current file under evaluation
     */
    private $file;

    /**
     * Inject test collection
     *
     * @param TestCollection $testCollection
     */
    public function __construct(TestCollection $testCollection)
    {
        $this->testCollection = $testCollection;
    }

    /**
     * Register callback on test failure
     *
     * @param  callable $callback
     * @return void
     */
    public function onTestFail(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Set file under evaluation
     *
     * @param  File $file
     * @return void
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * Interface function called when node is first hit
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        foreach ($this->testCollection as $test) {
            if ($test->evaluate($node, $this->file) === false) {
                call_user_func($this->callback, $test, $node, $this->file);
            }
        }
    }
}
