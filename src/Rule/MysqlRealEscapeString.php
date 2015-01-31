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
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        return !$this->isFunctionCall($node, 'mysql_real_escape_string');
    }
}
