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

    /**
     * @todo
     */
    public function getLongDescription()
    {
        return <<<EOD
The *display_errors* setting determines whether errors should be printed to the screen as part of the output or if they should be hidden from the user.

Displaying errors can be helpful during development, but should never be used in production as it may leak valueable information to an attacker.

To prevent accidentaly displaying errors it is recommended that you never use *ini_set()* to manually enable reporting.

*Example of failing code:*

<?php
    ini_set('display_errors', true);
?>

*How to fix?*

Configure display_errors in your *php.ini* file. And make sure that it is set to false in production.
EOD;
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
