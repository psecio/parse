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
        'awskey', 'aws_key',
        ];

    // These will have the delimiters added and be run case-insensitive
    private $sensitiveRegexList = [
        '([\w]+_?)?secret',
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
        return in_array($name, $this->sensitiveNames) ||
            $this->inRegexList($name, $this->sensitiveRegexList);
    }

    protected function inRegexList($str, $regexList)
    {
        foreach ($regexList as $regex) {
            if (preg_match("/$regex/i", $str)) {
                return true;
            }
        }

        return false;
    }
}
