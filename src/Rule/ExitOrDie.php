<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Scalar\LNumber;

/**
 * Exit or die usage should be avoided
 */
class ExitOrDie implements RuleInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    public function getDescription()
    {
        return 'Avoid the use of `exit` or `die` with strings as it could lead to injection issues (direct output)';
    }

    /**
     * @todo
     */
    public function getLongDescription()
    {
        return 'TODO';
    }

    public function isValid(Node $node)
    {
        return (
            !$this->isExpression($node, 'Exit')
            || is_null($node->expr)
            || ($node->expr instanceof LNumber)
        );
    }
}
