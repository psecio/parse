<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use eval. Ever.
 */
class EvalFunction implements RuleInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    public function getDescription()
    {
        return "Don't use eval. Ever.";
    }

    public function isValid(Node $node)
    {
        return !$this->isExpression($node, 'Eval');
    }
}
