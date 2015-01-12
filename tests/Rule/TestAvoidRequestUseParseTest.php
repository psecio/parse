<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class TestAvoidRequestUseParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$x = $_REQUEST["x"];', false],
            ['$x = $REQUEST[1];', true],
            ['$x = $y;', true],
            ];
    }

    protected function buildTest()
    {
        return new TestAvoidRequestUse();
    }
}
