<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestImportRequestVariablesParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['import_request_variables();', false],
            ];
    }

    protected function buildTest()
    {
        return new TestImportRequestVariables(false);
    }
}
