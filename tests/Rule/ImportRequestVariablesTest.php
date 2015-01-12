<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class ImportRequestVariablesTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['import_request_variables();', false],
        ];
    }

    protected function buildTest()
    {
        return new ImportRequestVariables();
    }
}
