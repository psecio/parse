<?php

namespace Psecio\Parse\Unittest\Tests;

use Psecio\Parse\Unittest\ParseTest;
use Psecio\Parse\Tests\TestAvoidRequestUse;

class TestAvoidRequestUse_ParseTest extends ParseTest
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
        return new TestAvoidRequestUse(false);
    }
}
