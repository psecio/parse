<?php

namespace Psecio\Parse;

use Mockery as m;
use Akamon\MockeryCallableMock\MockeryCallableMock;

class CallbackVisitorTest extends \PHPUnit_Framework_TestCase
{
    public function testCallback()
    {
        $node = m::mock('\PhpParser\Node');
        $file = m::mock('\Psecio\Parse\File');

        $falseTest = m::mock('Test')
            ->shouldReceive('evaluate')
            ->once()
            ->with($node, $file)
            ->andReturn(false)
            ->mock();

        $trueTest = m::mock('Test')
            ->shouldReceive('evaluate')
            ->once()
            ->with($node, $file)
            ->andReturn(true)
            ->mock();

        $testCollection = m::mock('\Psecio\Parse\TestCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([$falseTest, $trueTest]))
            ->mock();

        $visitor = new CallbackVisitor($testCollection);

        // Callback is called ONCE with failing test
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($falseTest, $node, $file)->once();
        $visitor->onTestFail($callback);

        $visitor->setFile($file);
        $visitor->enterNode($node);
    }
}
