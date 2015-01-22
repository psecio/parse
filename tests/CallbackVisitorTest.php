<?php

namespace Psecio\Parse;

use Mockery as m;
use Akamon\MockeryCallableMock\MockeryCallableMock;

class CallbackVisitorTest extends \PHPUnit_Framework_TestCase
{
    private $docCommentFactory;

    public function setUp()
    {
        $this->docCommentFactory = m::mock('\Psecio\Parse\DocComment\DocCommentFactoryInterface');
    }
    public function testCallback()
    {
        $node = m::mock('\PhpParser\Node')
            ->shouldReceive('getDocComment')
            ->andReturn('')
            ->zeroOrMoreTimes()
            ->mock();

        $falseCheck = $this->mockTest($node, false)
            ->once()
            ->mock();

        $trueCheck = $this->mockTest($node, true)
            ->once()
            ->mock();

        $ruleCollection = m::mock('\Psecio\Parse\RuleCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([$falseCheck, $trueCheck]))
            ->mock();

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, false);

        $file = m::mock('\Psecio\Parse\File');
        $visitor->setFile($file);

        // Callback is called ONCE with failing check
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($falseCheck, $node, $file)->once();
        $visitor->onNodeFailure($callback);

        $visitor->enterNode($node);
    }

    public function testIgnoreAnnotation()
    {
        $node = m::mock('\PhpParser\Node')
            ->shouldReceive('getDocComment')
            ->andReturn('@psecio\parse\enable truish')
            ->zeroOrMoreTimes()
            ->mock();

        $trueCheck = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn('truish')
            ->zeroOrMoreTimes()
            ->shouldReceive('isValid')
            ->with($node)
            ->andReturn(true);

        $ruleCollection = m::mock('\Psecio\Parse\RuleCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator([$trueCheck]))
            ->mock();

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, false);
        $visitor->setFile(m::mock('\Psecio\Parse\File'));

    }

    protected function mockTest($node, $isValidReturns, $name = 'name')
    {
        return m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn($name)
            ->zeroOrMoreTimes()
            ->shouldReceive('isValid')
            ->with($node)
            ->andReturn($isValidReturns);
    }
}
