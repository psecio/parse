<?php

namespace Psecio\Parse\Rule;

class SystemFunctionsTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['EXEC();',         false],
            ['exec();',         false],
            ['passthru();',     false],
            ['system();',       false],
            ['$x = `ls -al`;',  false],
            ['another_func();', true],
        ];
    }

    protected function buildTest()
    {
        return new SystemFunctions();
    }
}
