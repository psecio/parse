<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestUseReadfileParseTest extends ParseTest
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
        return new TestUseReadfile();
    }
}
