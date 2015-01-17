<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Do not use the eval modifier in regular expressions (\e)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class RegexWithExecModifier implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait;

    private $functions = ['preg_match', 'preg_match_all'];

    public function isValid(Node $node)
    {
        $nodeName = (is_object($node->name)) ? $node->name->parts[0] : $node->name;

        if ($node instanceof \PhpParser\Node\Expr\FuncCall && in_array(strtolower($nodeName), $this->functions)) {
            $regex = (string)$node->args[0]->value->value;
            if (strstr($regex, '/e') !== false) {
                return false;
            }
        }

        return true;
    }
}
