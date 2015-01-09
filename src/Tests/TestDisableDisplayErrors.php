<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;
use PhpParser\Node\Arg;

/**
 * The "display_errors" setting should not be enabled manually
 */
class TestDisableDisplayErrors implements TestInterface
{
    use Helper\NameTrait, Helper\IsFunctionTrait, Helper\isBoolLiteralTrait;

    /**
     * @var array List of allowed display_errors settings
     */
    private $allowed = [0, '0', false, 'false', 'off', 'stderr'];

    public function getDescription()
    {
        return 'The "display_errors" setting should not be enabled manually';
    }

    public function evaluate(Node $node, File $file)
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
