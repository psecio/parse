<?php

namespace Psecio\Parse\Rule;

class RunkitImportTest extends RuleTestCase
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
        return new RunkitImport();
    }
}
