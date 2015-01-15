<?php

namespace Psecio\Parse\Event;

use Mockery as m;

class IssueEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRuleAndNode()
    {
        $rule = m::mock('\Psecio\Parse\RuleInterface');
        $node = m::mock('\PhpParser\Node');
        $file = m::mock('\Psecio\Parse\File');

        $event = new IssueEvent($rule, $node, $file);

        $this->assertSame(
            $rule,
            $event->getRule()
        );

        $this->assertSame(
            $node,
            $event->getNode()
        );
    }
}
