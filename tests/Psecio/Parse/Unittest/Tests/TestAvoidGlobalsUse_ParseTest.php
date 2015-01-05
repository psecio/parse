<?php

namespace Psecio\Parse\Unittest\Tests;

use \Psecio\Parse\Unittest\ParseTest;
use Psecio\Parse\Tests\TestAvoidGlobalsUse;

class TestAvoidGlobalsUse_ParseTest extends ParseTest
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
