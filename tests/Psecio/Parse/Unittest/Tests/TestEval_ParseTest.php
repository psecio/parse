<?php

namespace Psecio\Parse\Unittest\Tests;

use Psecio\Parse\Unittest\ParseTest;
use Psecio\Parse\Tests\TestEval;

class TestEval_ParseTest extends ParseTest
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
