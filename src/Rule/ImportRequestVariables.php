<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Dont use import_request_variables as it puts them into the global scope
 * This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 */
class ImportRequestVariables implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait;

    public function getDescription()
    {
        return 'Avoid use of import_request_variables, deprecated in 5.3.0 and removed in 5.4.0';
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
        return !$this->isFunction($node, 'import_request_variables');
    }
}
