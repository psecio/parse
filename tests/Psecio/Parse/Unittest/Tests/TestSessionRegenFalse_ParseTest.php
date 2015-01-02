<?php

namespace Psecio\Parse\Unittest\Tests;

use \Psecio\Parse\Unittest\ParseTest;
use \Psecio\Parse\Tests\TestSessionRegenFalse;

class TestSessionRegenFalse_ParseTest extends ParseTest
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
