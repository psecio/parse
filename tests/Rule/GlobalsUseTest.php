<?php

namespace Psecio\Parse\Rule;

class GlobalsUseTest extends RuleTestCase
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
