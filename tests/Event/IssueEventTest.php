<?php

namespace Psecio\Parse\Event;

use Mockery as m;

class IssueEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTestAndNode()
    {
        $test = m::mock('\Psecio\Parse\TestInterface');
        $node = m::mock('\PhpParser\Node');
        $file = m::mock('\Psecio\Parse\File');

        $event = new IssueEvent($test, $node, $file);

        $this->assertSame(
            $test,
            $event->getTest()
        );

        $this->assertSame(
            $node,
            $event->getNode()
        );
    }
}
