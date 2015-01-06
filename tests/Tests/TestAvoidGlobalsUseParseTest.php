<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestAvoidGlobalsUseParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$x = $GLOBALS["x"];', false],
            ['$x = $y;', true],
            ];
    }

    protected function buildTest()
    {
        return new TestAvoidGlobalsUse(false);
    }
}
