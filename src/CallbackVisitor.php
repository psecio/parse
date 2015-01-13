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
     * @var callable Fail callback
     */
    private $callback;

    /**
     * @var File Current file under evaluation
     */
    private $file;

    /**
     * Inject rule collection
     *
     * @param RuleCollection $ruleCollection
     */
    public function __construct(RuleCollection $ruleCollection)
    {
        $this->ruleCollection = $ruleCollection;
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
        foreach ($this->ruleCollection as $rule) {
            if (!$rule->isValid($node)) {
                call_user_func($this->callback, $rule, $node, $this->file);
            }
        }
    }
}
