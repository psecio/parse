<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use eval. Ever.
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class EvalFunction implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsExpressionTrait;

    public function isValid(Node $node)
    {
        return !$this->isExpression($node, 'Eval');
    }
}
