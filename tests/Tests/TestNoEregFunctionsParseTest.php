<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestNoEregFunctionsParseTest extends ParseTest
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
        return new TestNoEregFunctions(false);
    }
}
