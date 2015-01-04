<?php

namespace Psecio\Parse\Unittest\Tests;

use Psecio\Parse\Unittest\ParseTest;
use Psecio\Parse\Tests\TestExitOrDie;

class TestExitOrDie_ParseTest extends ParseTest
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
