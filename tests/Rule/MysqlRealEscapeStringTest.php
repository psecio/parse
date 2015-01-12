<?php

namespace Psecio\Parse\Rule;

class MysqlRealEscapeStringTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['mysql_real_escape_string();', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new MysqlRealEscapeString();
    }
}
