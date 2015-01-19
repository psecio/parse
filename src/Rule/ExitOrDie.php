<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Scalar\LNumber;

/**
 * Avoid the use of `exit` or `die` with strings as it could lead to injection issues (direct output)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class ExitOrDie implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsExpressionTrait;

    public function isValid(Node $node)
    {
        return (
            !$this->isExpression($node, 'Exit')
            || is_null($node->expr)
            || ($node->expr instanceof LNumber)
        );
    }
}
