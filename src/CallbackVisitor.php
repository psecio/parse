<?php

namespace Psecio\Parse;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

/**
 * Evaluate rules and call callback on failure
 */
class CallbackVisitor extends NodeVisitorAbstract
{
    /**
     * @var RuleCollection Rules to evaluate
     */
    private $ruleCollection;

    /**
     * @var array List of enabled rules, stored as ruleName => bool
     */
    private $enabledRules;

    /**
     * @var callable Fail callback
     */
    private $callback;

    /**
     * @var File Current file under evaluation
     */
    private $file;

    /**
     * @var bool  If false, ignore all annotations
     */
    private $useAnnotations;

    /**
     * Inject rule collection
     *
     * @param RuleCollection $ruleCollection
     * @param bool           $useAnnotations  If false, ignore all annotations
     */
    public function __construct(RuleCollection $ruleCollection, $useAnnotations = true)
    {
        $this->ruleCollection = $ruleCollection;

        $this->enabledRules = [];
        foreach ($this->ruleCollection as $rule) {
            $this->enabledRules[strtolower($rule->getName())] = true;
        }

        $this->useAnnotations = $useAnnotations;
    }

    /**
     * Register failure callback
     *
     * @param  callable $callback
     * @return void
     */
    public function onNodeFailure(callable $callback)
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
        if ($this->useAnnotations) {
            $this->updateRuleFilters($node);
        }

        foreach ($this->ruleCollection as $rule) {
            if (!$this->enabledRules[strtolower($rule->getName())]) {
                continue;
            }

            if (!$rule->isValid($node)) {
                call_user_func($this->callback, $rule, $node, $this->file);
            }
        }
    }

    public function leaveNode(Node $node)
    {
        if (!$node->hasAttribute('oldEnabledRules')) {
            return;
        }

        // Restore rules as they were before this node
        $this->enabledRules = $node->getAttribute('oldEnabledRules');
    }

    private function updateRuleFilters($node)
    {
        $docBlock = $node->getDocComment();
        if (empty($docBlock)) {
            return false;
        }

        $node->setAttribute('oldEnabledRules', $this->enabledRules);
        $this->enabledRules = $this->evalDocBlock($docBlock, $this->enabledRules);
    }

    private function evalDocBlock($docBlock, $initialEnabledRules)
    {
        $enabledRules = $initialEnabledRules;

        // This can easily be made more generic:
        //   1. Replace "(en|dis)able" with "([a-z0-9_]+)" (and fix handling of first token)
        //   2. Replace "\s+([_a-z0-9]+)" with "(?:\s+([_a-z0-9]+))*"
        $cmdFlagRegex = '/^\s*\*\s+' // Look for 0 or more whitespace, a "*", and one or more whitespace characters
            . '@psecio\\\\parse\\\\' // Followed by "@psecio\parse" (quad-escape the \ to escape from PHP and preg)
            . '(en|dis)able'         // And either enable or disable store en or dis
            . '\s+(\w+)'             // Then 1 or more whitespace and a symbol, storing the symbol
            . '\s*$'                 // Then 0 or more whitespace and the end of the line
            . '/im';                 // Ignore case, treat source as multilined

        $flags = null;

        // Strip off the initial "/*" and trailing "*/", allowing for short blocks
        $docBlock = substr($docBlock, 2, -2);

        preg_match_all($cmdFlagRegex, $docBlock, $flags, PREG_SET_ORDER);

        if (empty($flags)) {
            return $enabledRules;
        }

        foreach ($flags as $flag) {
            $rule = strtolower($flag[2]);
            $enabledRules[$rule] = ($flag[1] == 'en');
        }

        return $enabledRules;
    }
}
