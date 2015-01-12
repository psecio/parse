<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class TestUseSystemFunctionsParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['exec();', false],
            ['passthru();', false],
            ['system();', false],
            ['$x = `ls -al`;', false],
            ['another_function();', true],
            ];
    }

    protected function buildTest()
    {
        return new TestUseSystemFunctions();
    }
}
