<?php

namespace Psecio\Parse\Tests;

/**
 * Check for output functions that use a variable in the output
 */
class TestOutputWithVariable extends \Psecio\Parse\Test
{
	private $outputFunctions = array(
		'print_r', 'printf', 'vprintf', 'sprintf'
	);

	public function evaluate($node, $file = null)
	{
		// See if our echo or print (constructs) uses concat
		if ($node instanceof \PhpParser\Node\Stmt\Echo_ || $node instanceof PhpParser\Node\Expr\Print_) {
			if (isset($node->exprs[0]) && $node->exprs[0] instanceof PhpParser\Node\Expr\BinaryOp\Concat) {
				return false;
			}
		}

		// See if our other output functions use concat
		if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array($node->name, $this->outputFunctions)) {
			if (isset($node->args[0]) && $node->args[0]->value instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
				return false;
			}
		}

		return true;
	}
}