<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\Variable;

/**
 * Avoid the use of an output method (echo, print, etc) directly with a variable
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class OutputWithVariable implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        // See if our echo or print (constructs) uses concat
        if ($node instanceof Echo_ || $node instanceof Print_) {
            if (isset($node->exprs[0]) && $node->exprs[0] instanceof Concat) {
                // See if either of the items is a variable
                if ($node->exprs[0]->left instanceof Variable || $node->exprs[0]->right instanceof Variable) {
                    return false;
                }
            }
        }

        // See if our other output functions use concat
        if ($this->isFunctionCall($node, ['print_r', 'printf', 'vprintf', 'sprintf'])) {
            if ($this->getCalledFunctionArgument($node, 0)->value instanceof Concat) {
                return false;
            }
        }

        return true;
    }
}
