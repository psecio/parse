<?php

namespace Psecio\Parse;

use Psecio\Parse\Fakes\FakeRule;
use Psecio\Parse\Fakes\FakeNode;
use Psecio\Parse\Fakes\FakeDocComment;
use Psecio\Parse\Fakes\FakeDocCommentFactory;
use Mockery as m;
use Akamon\MockeryCallableMock\MockeryCallableMock;
use PhpParser\Node;

class CallbackVisitorTest extends \PHPUnit_Framework_TestCase
{
    private $docCommentFactory;
    private $file;

    public function setUp()
    {
        $this->docCommentFactory = new FakeDocCommentFactory();
        $this->file = m::mock('\Psecio\Parse\File');
    }

    public function testCallback()
    {
        $node = new FakeNode();

        $falseCheck = new FakeRule('failed');

        $trueCheck = $this->getMockRule($node, true)->mock();
        //$trueCheck = new FakeRule('passed', [$node]);

        $ruleCollection = $this->getMockCollection([$falseCheck, $trueCheck]);

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, false);

        $visitor->setFile($this->file);

        // Callback is called ONCE with failing check
        $this->assertFailureCalled(1, $falseCheck, $node, $visitor);
    }

    public function testIgnoreAnnotation()
    {
        $ruleName = 'dontIgnoreRule';
        $node = new FakeNode('disable');
        $rule = new FakeRule($ruleName, []);
        $ruleCollection = $this->getMockCollection([$rule]);
        $this->addDoc($node, [$ruleName], []);

        // The false means to ignore annotations
        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, false);
        $visitor->setFile($this->file);

        // Callback is called once with failing check
        $this->assertFailureCalled(1, $rule, $node, $visitor);
    }

    public function testAnnotation()
    {
        $ruleName = 'ignoreRule';
        $node = new FakeNode('disable');
        $rule = new FakeRule($ruleName, []);
        $ruleCollection = $this->getMockCollection([$rule]);
        $this->addDoc($node, [$ruleName], []);

        // The true means to use annotations
        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($this->file);

        // Callback is called once with failing check
        $this->assertFailureCalled(0, $rule, $node, $visitor);
    }

    public function testAnnotationComment()
    {
        $ruleName = 'aRule';
        $node = new FakeNode('disable');
        $rule = new FakeRule($ruleName);
        $ruleCollection = $this->getMockCollection([$rule]);
        $block = new FakeDocComment('', [$ruleName . ' // ignore this'], []);
        $this->docCommentFactory->addDocComment($node->getDocComment(), $block);

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($this->file);

        $this->assertFailureCalled(0, $rule, $node, $visitor);
    }

    public function testRuleWithSpaces()
    {
        // While having a rule with a space is not currently possible (it wouldn't
        // ever match normally), this makes sure the parser is allowing spaces before
        // the comment mark.
        $ruleName = 'a rule';
        $node = new FakeNode('disable');
        $rule = new FakeRule($ruleName, []);
        $ruleCollection = $this->getMockCollection([$rule]);
        $this->addDoc($node, [$ruleName], []);

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($this->file);

        $this->assertFailureCalled(0, $rule, $node, $visitor);
    }

    public function testAnnotationTree()
    {
        $ruleName = 'rule1';

        // Structure is:
        //   node1 - disables rule
        //     node2 - re-enables rule (flagged invalid)
        //     node3 - doesn't set anything (disabled)
        //   node4 - doesn't set anything (enabled) (flagged invalid)
        // All nodes fail rule
        $node1 = new FakeNode('disable');
        $node2 = new FakeNode('enable');
        $node3 = new FakeNode();
        $node4 = new FakeNode();

        $rule = new FakeRule($ruleName, []);
        $ruleCollection = $this->getMockCollection([$rule]);
        $this->addDoc($node1, [$ruleName], []);
        $this->addDoc($node2, [], [$ruleName]);

        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($this->file);

        $callList = [];
        $callback = function (RuleInterface $r, Node $n, File $f) use (&$callList) {
            $callList[] = [$r, $n, $f];
        };

        $expectedCallList = [
            [$rule, $node2, $this->file],
            [$rule, $node4, $this->file],
        ];

        $visitor->onNodeFailure($callback);

        $visitor->enterNode($node1);
        $visitor->enterNode($node2);
        $visitor->leaveNode($node2);
        $visitor->enterNode($node3);
        $visitor->leaveNode($node3);
        $visitor->leaveNode($node1);

        $visitor->enterNode($node4);
        $visitor->leaveNode($node4);

        $this->assertEquals($expectedCallList, $callList);
    }

    public function testEmptyDocBlock()
    {
        $ruleName = 'aRule';

        $node = new FakeNode();
        $falseCheck = new FakeRule($ruleName, []);
        $ruleCollection = $this->getMockCollection([$falseCheck]);

        // The true means to use annotations
        $visitor = new CallbackVisitor($ruleCollection, $this->docCommentFactory, true);
        $visitor->setFile($this->file);

        // Callback is called once with failing check
        $this->assertFailureCalled(1, $falseCheck, $node, $visitor);
    }

    private function addDoc($node, $disabled, $enabled)
    {
        $block = new FakeDocComment('', $disabled, $enabled);
        $this->docCommentFactory->addDocComment($node->getDocComment(), $block);
    }

    private function assertFailureCalled($times, RuleInterface $rule,
                                         Node $node, CallbackVisitor $visitor)
    {
        $callback = new MockeryCallableMock();
        $callback->shouldBeCalled()->with($rule, $node, $this->file)->times($times);
        $visitor->onNodeFailure($callback);
        $visitor->enterNode($node);
    }

    private function getMockRule($node, $isValidReturns, $name = 'name')
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

    private function getMockCollection($ruleList)
    {
        $ruleCollection = m::mock('\Psecio\Parse\RuleCollection')
            ->shouldReceive('getIterator')
            ->andReturn(new \ArrayIterator($ruleList))
            ->mock();

        return $ruleCollection;
    }
}
