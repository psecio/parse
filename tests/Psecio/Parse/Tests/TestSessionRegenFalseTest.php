<?php

namespace Psecio\Parse;

use \Mockery;

class TestSessionRegenFalseTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        // Mockery must be shut down
        Mockery::close();
    }

    public function test_notFunction_true()
    {
        $this->assertTrue(
        	$this->evalTest(
        		$this->makeNode(false)
        	),
        	"If the node isn't a function or not the correct function, can't fail the test"
        );
    }

    public function test_functionNoArgs_false()
    {
        $node = $this->makeNode(true);
        $node->args = array();

        $this->assertFalse(
        	$this->evalTest($node),
        	"If it's the correct function, and there are no arguments, the test should fail"
        );
    }

    public function test_functionFalseArg_false()
    {
        $node = $this->makeNode(true);
        $arg = (object)array('value' => $this->makeNamedNode('false'));
        $node->args = array($arg);

        $this->assertFalse(
        	$this->evalTest($node),
        	"If it's the correct function and the argument is false, the test should fail"
        );
    }

    public function test_functionTrueArg_true()
    {
        $node = $this->makeNode(true);
        $arg = (object)array('value' => $this->makeNamedNode('true'));
        $node->args = array($arg);

        $this->assertTrue(
        	$this->evalTest($node),
        	"If it's the correct function and the argument is true, the test should succeed"
        );
    }

    public function test_functionNonBoolArg_false()
    {
        $node = $this->makeNode(true);
        $arg = (object)array('value' => $this->makeNamedNode('notBool'));
        $node->args = array($arg);

        $this->assertFalse(
        	$this->evalTest($node),
        	"If it's the correct function and the argument is non-boolean, the test should fail"
        );
    }

    /**
     * Make a (mocked) node for testing against TestSessionRegenFalse()
     *
     * @param  bool $isFuncReturns  Value that $node->isFunction('session_regenerate_id') should return
     * @return Node that returns $isFuncReturns when isFunction() is called
     */
    protected function makeNode($isFuncReturns)
    {
        return Mockery::mock('Node')
            ->shouldReceive('isFunction')
            ->with('session_regenerate_id')
            ->andReturn($isFuncReturns)
            ->mock();
    }

    /**
     * Make a (mocked) node object that has a name property that is a \PhpParse\Node\Name with a value of $value
     *
     * @param  string $name What to set the name string value to
     * @return \PhpParser\Node With appropriate name property
     */
    protected function makeNamedNode($name)
    {
        $node = Mockery::mock('\PhpParser\Node');
        $node->name = Mockery::mock('\PhpParser\Node\Name')
        	->shouldReceive('__toString')
            ->zeroOrMoreTimes()
            ->andReturn($name)
            ->mock();

        return $node;
    }

    /**
     * Evaluate the TestSessionRegenFalse() test
     *
     * @param  Node $node The node to evaluate
     * @return bool The result of calling Tests\TestSessionRegenFalse::evaluate($node)
     */
    protected function evalTest($node)
    {
        return (new Tests\TestSessionRegenFalse(null))->evaluate($node);
    }
}
