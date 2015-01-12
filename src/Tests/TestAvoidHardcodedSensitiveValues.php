<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\TestInterface;
use PhpParser\Node;
use Psecio\Parse\File;

/**
 * Avoid hard-coding sensitive values (ex. "username", "password", etc.)
 */
class TestAvoidHardcodedSensitiveValues implements TestInterface
{
    use Helper\NameTrait, Helper\IsExpressionTrait;

    private $sensitiveNames = [
        'username', 'user_name', 'password', 'user', 'pass', 'pwd', 'pswd',
        'awskey', 'aws_key', 'secret',
        ];

    public function getDescription()
    {
        return 'Avoid hard-coding sensitive values (ex. "username", "password", etc.)';
    }

    public function evaluate(Node $node, File $file)
    {
        // Fail on straight $var = 'value', where $var is in $sensitiveNames
        return !($this->isExpression($node, 'Assign') &&
                 $this->isSensitiveName($node->var->name) &&
                 ($node->expr instanceof \PhpParser\Node\Scalar\String));
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
            if ($name === $match
                || $this->startsWith($name, $match)
                || $this->endsWith($name, $match)) {
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
