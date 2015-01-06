<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestExitOrDieParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            // Shouldn't exit with a string
            ['exit("message");', false],
            ['die("message");', false],
            ];
    }

    protected function buildTest()
    {
        return new TestExitOrDie(false);
    }
}
