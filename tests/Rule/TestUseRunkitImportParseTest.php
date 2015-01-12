<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class TestUseRunkitImportParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['runkit_import();', false],
            ['another_function();', true],
            ];
    }

    protected function buildTest()
    {
        return new TestUseRunkitImport();
    }
}
