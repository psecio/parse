<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Print_;
use PhpParser\Node\Expr\FuncCall;

/**
 * Check for output functions that use a variable in the output
 */
class OutputWithVariable implements RuleInterface
{
    use Helper\NameTrait;

    private $outputFunctions = ['print_r', 'printf', 'vprintf', 'sprintf'];

    public function getDescription()
    {
        return 'Avoid the use of an output method (echo, print, etc) directly with a variable';
    }

    public function isValid(Node $node)
    {
        // See if our echo or print (constructs) uses concat
        if ($node instanceof Echo_ || $node instanceof Print_) {
            if (isset($node->exprs[0]) && $node->exprs[0] instanceof Concat) {
                return false;
            }
        }

        // See if our other output functions use concat
        if ($node instanceof FuncCall && in_array($node->name, $this->outputFunctions)) {
            if (isset($node->args[0]) && $node->args[0]->value instanceof Concat) {
                return false;
            }
        }

        return true;
    }
}
