<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestUseParseStrParseTest extends ParseTest
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
        return new TestUseParseStr();
    }
}
