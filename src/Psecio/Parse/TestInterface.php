<?php

namespace Psecio\Parse;

use PhpParser\Node;

/**
 * Code checks implement this interface
 */
interface TestInterface
{
    /**
     * Get test name
     *
     * @return string
     */
    public function getName();

    /**
     * Get test description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Execute test on node
     *
     * @param  Node $node Current node instance
     * @param  File $file Current file instance
     * @return boolean Pass/fail of the evaluation
     */
    public function evaluate(Node $node, File $file);
}
