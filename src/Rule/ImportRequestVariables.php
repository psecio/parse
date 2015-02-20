<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Dont use 'import_request_variables' as it puts them into the global scope
 *
 * This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 *
 * @todo Add long description to docblock
 */
class ImportRequestVariables implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        return !$this->isFunctionCall($node, 'import_request_variables');
    }
}
