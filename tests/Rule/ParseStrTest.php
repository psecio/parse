<?php

namespace Psecio\Parse\Rule;

class ParseStrTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['parse_str(\'text\');', false],
            ['parse_str("text");', false],
            ['parse_str($var1);', false],
            ['parse_str(\'text\', $var2);', true],
            ['parse_str("text", $var2);', true],
            ['parse_str($var1, $var2);', true],
            ['mb_parse_str();', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new ParseStr();
    }
}
