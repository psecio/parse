<?php

namespace Psecio\Parse;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

use Psecio\Parse\DocComment\DocCommentFactoryInterface;

/**
 * Evaluate rules and call callback on failure
 */
class CallbackVisitor extends NodeVisitorAbstract
{
    const ENABLE_TAG = 'psecio\parse\enable';
    const DISABLE_TAG = 'psecio\parse\disable';

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
     * @var DocCommentFactoryInterface
     */
    private $docCommentFactory;

    /**
     * Inject rule collection
     *
     * @param RuleCollection $ruleCollection
     * @param bool           $useAnnotations  If false, ignore all annotations
     */
    public function __construct(RuleCollection $ruleCollection,
                                DocCommentFactoryInterface $docCommentFactory,
                                $useAnnotations)
    {
        $this->ruleCollection = $ruleCollection;
        $this->enabledRules = [];

        foreach ($this->ruleCollection as $rule) {
            $this->enabledRules[strtolower($rule->getName())] = true;
        }

        $this->useAnnotations = $useAnnotations;
        $this->docCommentFactory = $docCommentFactory;
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
            $this->evaluateRule($node, $rule);
        }
    }

    protected function evaluateRule($node, $rule)
    {
        if (!$this->enabledRules[strtolower($rule->getName())]) {
            return;
        }

        if (!$rule->isValid($node)) {
            call_user_func($this->callback, $rule, $node, $this->file);
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
            return;
        }

        $node->setAttribute('oldEnabledRules', $this->enabledRules);
        $this->enabledRules = $this->evaluateDocBlock($docBlock, $this->enabledRules);
    }

    private function evaluateDocBlock($docBlock, $rules)
    {
        $comment = $this->docCommentFactory->createDocComment($docBlock);

        $this->checkTags($comment, $rules, self::ENABLE_TAG, true);
        $this->checkTags($comment, $rules, self::DISABLE_TAG, false);

        return $rules;
    }

    private function checkTags(DocComment\DocCommentInterface $comment, &$rules, $tag, $value)
    {
        $tags = $comment->getIMatchingTags($tag);

        foreach ($tags as $rule) {
            // Get the first word from content. This allows you to add comments to rules.
            $ruleName = trim(strtolower(strtok($rule, '//')));
            $rules[$ruleName] = $value;
        }
    }
}
