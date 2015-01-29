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
        $node = $this->getMockNode()
            ->mock();

        $falseCheck = $this->getMockRule($node, false)
            ->once()
            ->mock();

        $trueCheck = $this->getMockRule($node, true)
            ->once()
            ->mock();

        $ruleCollection = $this->getMockCollection([$falseCheck, $trueCheck]);

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
        $ruleName = 'dontIgnoreRule';
        $node = $this->getMockNodeWithAnnotation('disable', $ruleName);
        $falseCheck = $this->getMockRule($node, false, $ruleName)->mock();
        $ruleCollection = $this->getMockCollection([$falseCheck]);
        $file = m::mock('\Psecio\Parse\File');

        // The false means to ignore annotations
        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, false);
        $visitor->setFile($file);

        // Callback is called once with failing check
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($falseCheck, $node, $file)->once();
        $visitor->onNodeFailure($callback);

        $visitor->enterNode($node);
    }

    public function testAnnotation()
    {
        $ruleName = 'ignoreRule';
        $node = $this->getMockNodeWithAnnotation('disable', $ruleName);
        $falseCheck = $this->getMockRule($node, false, $ruleName)->mock();
        $ruleCollection = $this->getMockCollection([$falseCheck]);
        $file = m::mock('\Psecio\Parse\File');

        // The true means to use annotations
        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($file);

        // Callback is called once with failing check
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($falseCheck, $node, $file)->never();
        $visitor->onNodeFailure($callback);

        $visitor->enterNode($node);
    }

    protected function getMockRule($node, $isValidReturns, $name = 'name')
    {
        $m = m::mock('\Psecio\Parse\RuleInterface')
             ->shouldReceive('getName')
             ->andReturn($name)
             ->zeroOrMoreTimes()
             ->shouldReceive('isValid')
             ->with($node)
             ->andReturn($isValidReturns);

        return $m;
    }

    protected function getMockNodeWithAnnotation($annotation, $rule)
    {
        $node = $this->getMockNode('@psecio\parse\\' . $annotation . ' ' . $rule)
            ->shouldReceive('setAttribute')
            ->mock();

        return $node;
    }

    protected function getMockCollection($ruleList)
    {
        $ruleCollection = m::mock('\Psecio\Parse\RuleCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator($ruleList))
            ->mock();

        return $ruleCollection;
    }

    protected function getMockNode($docBlock = '')
    {
        $node = m::mock('PhpParser\Node')
            ->shouldReceive('getDocComment')
            ->andReturn($docBlock)
            ->zeroOrMoreTimes()
            ;
        return $node;
    }
}
