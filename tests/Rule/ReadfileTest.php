<?php

namespace Psecio\Parse\Rule;

class ReadfileTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['readfile();', false],
            ['readlink();', false],
            ['readgzfile();', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new Readfile();
    }
}
