<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;

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

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'mysql_real_escape_string');
    }
}
