<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;
use PhpParser\Node\Arg;

/**
 * The "display_errors" setting should not be enabled manually
 */
class DisplayErrors implements RuleInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait, Helper\IsBoolLiteralTrait;

    /**
     * @var array List of allowed display_errors settings
     */
    private $allowed = [0, '0', false, 'false', 'off', 'stderr'];

    public function getDescription()
    {
        return 'The "display_errors" setting should not be enabled manually';
    }

    public function isValid(Node $node)
    {
        if ($this->isFunction($node, 'ini_set') && $this->readArg($node->args[0]) === 'display_errors') {
            return in_array($this->readArg($node->args[1]), $this->allowed, true);
        }
        return true;
    }

    private function readArg(Arg $arg)
    {
        if ($this->isBoolLiteral($arg->value)) {
            return (string)$arg->value->name;
        }
        return $arg->value->value;
    }
}
