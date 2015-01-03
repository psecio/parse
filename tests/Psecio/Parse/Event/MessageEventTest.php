<?php

namespace Psecio\Parse\Event;

use Mockery as m;

class MessageEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $this->assertSame(
            'my message',
            (new MessageEvent('my message', m::mock('\Psecio\Parse\File')))->getMessage()
        );
    }
}
