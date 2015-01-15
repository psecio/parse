<?php

namespace Psecio\Parse;

use Mockery as m;
use Akamon\MockeryCallableMock\MockeryCallableMock;

class CallbackVisitorTest extends \PHPUnit_Framework_TestCase
{
    public function testCallback()
    {
        $node = m::mock('\PhpParser\Node');

        $falseCheck = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('isValid')
            ->once()
            ->with($node)
            ->andReturn(false)
            ->mock();

        $trueCheck = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('isValid')
            ->once()
            ->with($node)
            ->andReturn(true)
            ->mock();

        $ruleCollection = m::mock('\Psecio\Parse\RuleCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([$falseCheck, $trueCheck]))
            ->mock();

        $visitor = new CallbackVisitor($ruleCollection);

        $file = m::mock('\Psecio\Parse\File');
        $visitor->setFile($file);

        // Callback is called ONCE with failing check
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($falseCheck, $node, $file)->once();
        $visitor->onNodeFailure($callback);

        $visitor->enterNode($node);
    }
}
