<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestSessionRegenFalseParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$x = 17;', true],
            ['session_regenerate_id();', false],
            ['session_regenerate_id(false);', false],
            ['session_regenerate_id(true);', true],
            ['session_regenerate_id(17);', false],
            ['$x["a"]("blah");', false],
            ['random_function();', true],
            ];
    }

    protected function buildTest()
    {
        return new TestSessionRegenFalse();
    }
}
