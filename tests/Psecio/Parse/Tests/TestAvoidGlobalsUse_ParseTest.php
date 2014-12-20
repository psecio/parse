<?php

namespace Psecio\Parse\Tests;

class TestAvoidGlobalsUse_ParseTest extends \tests\Psecio\Parse\ParseTest
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
