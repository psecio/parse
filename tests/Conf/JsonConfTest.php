<?php

namespace Psecio\Parse\Conf;

class JsonConfTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnMalformedJson()
    {
        $this->setExpectedException('RuntimeException');
        new JsonConf('this-is-not-a-valid-json-string');
    }

    public function testExceptionSchemaViolation()
    {
        $this->setExpectedException('RuntimeException');
        new JsonConf('{"undefined": "this property is not definied in the schema"}');
    }

    public function configurationProvider()
    {
        return [
            ['{}', 'getFormat',          ''],
            ['{}', 'getPaths',           []],
            ['{}', 'getIgnorePaths',     []],
            ['{}', 'getExtensions',      []],
            ['{}', 'getRuleWhitelist',   []],
            ['{}', 'getRuleBlacklist',   []],
            ['{}', 'disableAnnotations', false],
            ['{"format":"dots"}',             'getFormat',          'dots'],
            ['{"paths":["path"]}',            'getPaths',           ['path']],
            ['{"ignore-paths":["path"]}',     'getIgnorePaths',     ['path']],
            ['{"extensions":["php"]}',        'getExtensions',      ['php']],
            ['{"whitelist-rules":["rule"]}',  'getRuleWhitelist',   ['rule']],
            ['{"blacklist-rules":["rule"]}',  'getRuleBlacklist',   ['rule']],
            ['{"disable-annotations": true}', 'disableAnnotations', true],
        ];
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testConfiguration($json, $method, $expected)
    {
        $this->assertSame(
            $expected,
            (new JsonConf($json))->$method()
        );
    }
}
