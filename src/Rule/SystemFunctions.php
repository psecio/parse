<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Use of system functions, especially with user input, is not recommended
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class SystemFunctions implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait, Helper\IsExpressionTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, ['exec', 'passthru', 'system'])) {
            return false;
        }

        return !$this->isExpression($node, 'ShellExec');
    }
}
