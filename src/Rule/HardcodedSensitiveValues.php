<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Avoid hard-coding sensitive values (ex. "username", "password", etc.)
 *
 * Long description missing...
 *
 * @todo Add long description to docblock
 */
class HardcodedSensitiveValues implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsExpressionTrait, Helper\IsFunctionTrait;

    private $sensitiveNames = [
        'username', 'user_name', 'password', 'user', 'pass', 'pwd', 'pswd',
        'awskey', 'aws_key', 'secret',
    ];

    public function isValid(Node $node)
    {
        list($name, $value) = $this->getNameAndValue($node);
        if ($name === false) {
            return true;
        }

        // Fail on straight $var = 'value', where $var is in $sensitiveNames
        return !($this->isSensitiveName($name) &&
                 $value instanceof \PhpParser\Node\Scalar\String);
    }

    protected function getNameAndValue($node)
    {
        if ($this->isExpression($node, 'Assign')) {
            return [$node->var->name, $node->expr];
        }

        if ($node instanceof \PhpParser\Node\Const_) {
            return [$node->name, $node->value];
        }

        if ($this->isFunction($node, 'define')) {
            $name = $node->args[0]->value->value;
            $value = $node->args[1]->value;

            return [$name, $value];
        }

        return [false, false];
    }

    public function isSensitiveName($name)
    {
        if (!is_string($name)) {
            return false;
        }
        $name = strtolower($name);
        return $this->matchSearchList($name, $this->sensitiveNames);
    }

    protected function matchSearchList($name, $list)
    {
        foreach ($list as $match) {
            if ($this->startsWith($name, $match) || $this->endsWith($name, $match)) {
                return true;
            }
        }
    }

    protected function startsWith($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }

    protected function endsWith($haystack, $needle)
    {
        return strrpos($haystack, $needle) === (strlen($haystack) - strlen($needle));
    }
}
