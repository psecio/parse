<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestEvalParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['eval("asdfasdfasdf");', false],
            ['17 + eval("32 - 6");', false],
            ['17 + (32 - 6);', true],
            ];
    }

    protected function buildTest()
    {
        return new TestEval(false);
    }
}
