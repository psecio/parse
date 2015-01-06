<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestUseExecModifierInRegexParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['preg_match("/test/e", "to match");', false],
            ['preg_match("/test/", "to match");', true],
            ['preg_match_all("/test/e", "to match");', false],
            ['preg_match_all("/test/", "to match");', true],
            ];
    }

    protected function buildTest()
    {
        return new TestUseExecModifierInRegex(false);
    }
}
