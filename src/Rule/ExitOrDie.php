<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Expr\BinaryOp\Concat;

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
        // If it's an exit, see if there's any concatenation happening
        if ($this->isExpression($node, 'Exit') === true) {
            if ($node->expr instanceof Concat) {
                return false;
            }
        }
        return true;
    }
}
