<?php

namespace Psecio\Parse\Rule;

class ParseStrTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['parse_str();', false],
            ['mb_parse_str();', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new ParseStr();
    }
}
