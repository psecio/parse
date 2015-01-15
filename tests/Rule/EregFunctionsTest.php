<?php

namespace Psecio\Parse\Rule;

class EregFunctionsTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['ereg("pattern", "toMatch");', false],
            ['eregi("pattern", "toMatch");', false],
            ['ereg_replace("pattern", "replacment", "toMatch");', false],
            ['eregi_replace("pattern", "replacement", "toMatch");', false],
            ['preg_match("pattern", "toMatch");', true],
            ['preg_replace("pattern", "replacement", "toMatch");', true],
        ];
    }

    protected function buildTest()
    {
        return new EregFunctions();
    }
}
