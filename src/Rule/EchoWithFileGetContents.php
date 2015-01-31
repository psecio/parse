<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Expr\BinaryOp\Concat;

/**
 * Using 'echo' with results of 'file_get_contents' could lead to injection issues
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class EchoWithFileGetContents implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($node instanceof Echo_) {
            if (isset($node->exprs[0]) && $node->exprs[0] instanceof Concat) {
                // Check the right side
                if ($this->isFunctionCall($node->exprs[0]->right, 'file_get_contents')) {
                    return false;
                }
                // Check the left side
                if ($this->isFunctionCall($node->exprs[0]->left, 'file_get_contents')) {
                    return false;
                }
            }
        }

        return true;
    }
}
