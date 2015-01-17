<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Use of 'mysql_real_escape_string' is not recommended, use prepared statements instead
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class MysqlRealEscapeString implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    public function isValid(Node $node)
    {
        return !($node instanceof \PhpParser\Node\Expr\FuncCall && $node->name == 'mysql_real_escape_string');
    }
}
