<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Exit or die usage should be avoided
 */
class TestExitOrDie implements TestInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    public function getDescription()
    {
        return 'Avoid the use of `exit` or `die` (could lead to injection issues (direct output)';
    }

    public function evaluate(Node $node, File $file)
    {
        return !$this->isExpression($node, 'Exit');
    }
}
