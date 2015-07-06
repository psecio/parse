<?php

namespace Psecio\Parse\Conf;

use Mockery as m;

class DualConfTest extends \PHPUnit_Framework_TestCase
{
    public function cascadeProvider()
    {
        return [
            ['getFormat',          'first',   'second',   'first'   ],
            ['getFormat',          '',        'second',   'second'  ],
            ['getPaths',           ['first'], ['second'], ['first'] ],
            ['getPaths',           [],        ['second'], ['second']],
            ['getIgnorePaths',     ['first'], ['second'], ['first'] ],
            ['getIgnorePaths',     [],        ['second'], ['second']],
            ['getExtensions',      ['first'], ['second'], ['first'] ],
            ['getExtensions',      [],        ['second'], ['second']],
            ['getRuleWhitelist',   ['first'], ['second'], ['first'] ],
            ['getRuleWhitelist',   [],        ['second'], ['second']],
            ['getRuleBlacklist',   ['first'], ['second'], ['first'] ],
            ['getRuleBlacklist',   [],        ['second'], ['second']],
            ['disableAnnotations', false,     false,      false     ],
            ['disableAnnotations', true,      false,      true      ],
            ['disableAnnotations', false,     true,       true      ],
        ];
    }

    /**
     * @dataProvider cascadeProvider
     */
    public function testCascade($method, $first, $second, $expected)
    {
        $conf = new DualConf(
            m::mock('Psecio\Parse\Conf\Configuration')->shouldReceive($method)->andReturn($first)->mock(),
            m::mock('Psecio\Parse\Conf\Configuration')->shouldReceive($method)->andReturn($second)->mock()
        );
        $this->assertSame($expected, $conf->$method());
    }
}
