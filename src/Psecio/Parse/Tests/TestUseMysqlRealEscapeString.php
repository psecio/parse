<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Don't use the mysql_real_escape_string function, use bound params/prepared statements instead
 */
class TestUseMysqlRealEscapeString implements TestInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Use of mysql_real_escape_string is not recommended. Use prepared statements/bind variables.';
    }

    public function evaluate(Node $node, File $file)
    {
        return !($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'mysql_real_escape_string');
    }
}
