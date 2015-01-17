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
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionTrait, Helper\IsExpressionTrait;

    private $functions = ['exec', 'passthru', 'system'];

    public function isValid(Node $node)
    {
        if ($this->isFunction($node)) {
            $name = (is_object($node->name)) ? $node->name->parts[0] : $node->name;
            if (in_array(strtolower($name), $this->functions)) {
                return false;
            }
        }

        return !$this->isExpression($node, 'ShellExec');
    }
}
