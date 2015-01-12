<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class GlobalsUseTest extends ParseTest
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
        return new GlobalsUse();
    }
}
