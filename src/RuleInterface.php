<?php

namespace Psecio\Parse;

use PhpParser\Node;

/**
 * Code checks implement this interface
 */
interface RuleInterface
{
    /**
     * Get the rule name
     *
     * @return string
     */
    public function getName();

    /**
     * Get rule description
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
