<?php

namespace Psecio\Parse\Event;

use Mockery as m;

class ErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $this->assertSame(
            'my message',
            (new ErrorEvent('my message', m::mock('\Psecio\Parse\File')))->getMessage()
        );
    }
}
