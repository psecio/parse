<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class TestHttpRawPostDataParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$x = $http_raw_post_data;', false],
            ];
    }

    protected function buildTest()
    {
        return new TestHttpRawPostData();
    }
}
