<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use the mysql_real_escape_string function, use bound params/prepared statements instead
 */
class MysqlRealEscapeString implements RuleInterface
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
