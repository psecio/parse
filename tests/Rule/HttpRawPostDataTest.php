<?php

namespace Psecio\Parse\Rule;

class HttpRawPostDataTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['$x = $http_raw_post_data;', false],
        ];
    }

    protected function buildTest()
    {
        return new HttpRawPostData();
    }
}
