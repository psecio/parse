<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class ReadfileTest extends ParseTest
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
