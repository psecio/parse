<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Don't use $_REQUEST, know where your data is coming from
 */
class RequestUse implements RuleInterface
{
    use Helper\NameTrait;

    public function getDescription()
    {
        return 'Avoid the use of $_REQUEST (know where your data comes fron)';
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
        return !($node instanceof \PhpParser\Node\Expr\Variable && $node->name == '_REQUEST');
    }
}
