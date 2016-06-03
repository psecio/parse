<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

/**
 * Do not use the eval modifier (e) with preg_replace()
 *
 * With the <em>e</em> modifier set <em>preg_replace()</em> does normal substitution of
 * backreferences in the replacement string, evaluates it as PHP code, and
 * uses the result for replacing the search string.
 *
 * This modifier is deprecated as of PHP <em>5.5</em> and use is highly discouraged
 * as it can easily introduce security vulnerabilites.
 *
 * <strong>Example of failing code</strong>
 *
 * The following code can be easily exploited by passing in a string such as
 * <em><h1>{${eval($_GET[php_code])}}</h1></em>. This gives the attacker the ability
 * to execute arbitrary PHP code and as such gives him nearly complete access
 * to your server.
 *
 * <code>$html = preg_replace(
 *     '(<h([1-6])>(.*?)</h\1>)e',
 *     '"<h$1>" . strtoupper("$2") . "</h$1>"',
 *     $_POST['html']
 * );</code>
 *
 * <strong>How to fix?</strong>
 *
 * Use the <em>preg_replace_callback()</em> function instead.
 *
 * <code>$html = preg_replace_callback(
 *     '(<h([1-6])>(.*?)</h\1>)',
 *     function ($m) {
 *         return "<h$m[1]>" . strtoupper($m[2]) . "</h$m[1]>";
 *     },
 *     $_POST['html']
 * );</code>
 */
class PregReplaceWithEvalModifier implements RuleInterface
{
    use Helper\NameTrait, Helper\DocblockDescriptionTrait, Helper\IsFunctionCallTrait;

    public function isValid(Node $node)
    {
        if ($this->isFunctionCall($node, 'preg_replace')) {
            $value = $this->getCalledFunctionArgument($node, 0)->value;

            if (property_exists($value, 'value') && preg_match("/e[a-zA-Z]*$/", $value->value)) {
                return false;
            }
        }
        return true;
    }
}
