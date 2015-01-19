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
     * Get long description for this rule
     *
     * A long description should explain why this particular rule is important,
     * what could happen if the rule is ignored, include code examples that break
     * the rule and information on how to resolve.
     *
     * The following html tags may be used: <em> <strong> <code>
     *
     * @return string
     */
    public function getLongDescription();

    /**
     * Check if node is valid
     *
     * @param  Node $node
     * @return boolean
     */
    public function isValid(Node $node);
}
