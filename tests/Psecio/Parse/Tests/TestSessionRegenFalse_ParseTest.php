<?php

namespace Psecio\Parse\Tests;

class TestSessionRegenFalse_ParseTest extends \tests\Psecio\Parse\ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['session_regenerate_id();', false],
            ['session_regenerate_id(false);', false],
            ['session_regenerate_id(true);', true],
            ['$x["a"]("blah");', false],
            ['random_function();', true],
            ];
    }

    protected function buildTest()
    {
        return new TestSessionRegenFalse(false);
    }
}
