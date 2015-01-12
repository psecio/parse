<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class MysqlRealEscapeStringTest extends ParseTest
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
