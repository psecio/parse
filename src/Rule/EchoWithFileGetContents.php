<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Be sure you're not using an echo with a file_get_contents
 */
class EchoWithFileGetContents implements RuleInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Using `echo` with results of `file_get_contents` could lead to injection issues. ';
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
        if ($node instanceof \PhpParser\Node\Stmt\Echo_) {
            if (isset($node->exprs[0]) && $node->exprs[0] instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
                // Check the right side
                $right = $node->exprs[0]->right;
                if ($right instanceof \PhpParser\Node\Expr\FuncCall && $right->name == 'file_get_contents') {
                    return false;
                }

                // Check the left side
                $left = $node->exprs[0]->left;
                if ($left instanceof \PhpParser\Node\Expr\FuncCall && $left->name == 'file_get_contents') {
                    return false;
                }
            }
        }

        return true;
    }
}
