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
     * Check if node is valid
     *
     * @param  Node $node
     * @return boolean
     */
    public function isValid(Node $node);
}
