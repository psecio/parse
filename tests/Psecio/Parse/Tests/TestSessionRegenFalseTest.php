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

	/** If the node isn't a function or not the correct function, can't fail the test. */
	public function test_notFunction_true()
	{
		$node = $this->makeNode(false);
		$res = $this->evalTest($node);
		$this->assertTrue($res);
	}

	/** If it's the correct function, and there are no arguments, the test should fail */
	public function test_functionNoArgs_false()
	{
		$node = $this->makeNode(true);
		$node->args = array();

		$res = $this->evalTest($node);
		$this->assertFalse($res);
	}

	/** If it's the correct function and the argument is false, the test should fail. */
	public function test_functionFalseArg_false()
	{
		$node = $this->makeNode(true);
		$arg = (object)array('value' => $this->makeNamedNode('false'));
		$node->args = array($arg);
		$this->assertFalse($this->evalTest($node));
	}

	/** If it's the correct function and the argument is true, the test should succeed. */
	public function test_functionTrueArg_true()
	{
		$node = $this->makeNode(true);
		$arg = (object)array('value' => $this->makeNamedNode('true'));
		$node->args = array($arg);
		$this->assertTrue($this->evalTest($node));
	}

	/** If it's the correct function and the argument is non-boolean, the test should fail. */
	public function test_functionNonBoolArg_false()
	{
		$node = $this->makeNode(true);
		$arg = (object)array('value' => $this->makeNamedNode('notBool'));
		$node->args = array($arg);
		$this->assertFalse($this->evalTest($node));
	}

	/**
	 * Make a (mocked) node for testing against TestSessionRegenFalse()
	 *
	 * @param bool $isFuncReturns  Value that $node->isFunction('session_regenerate_id') should return
	 *
	 * @return Node (mocked) that returns $isFuncReturns when isFunction() is called
	 */
	protected function makeNode($isFuncReturns)
	{
		$node = Mockery::mock('Node')
			->shouldReceive('isFunction')
			->with('session_regenerate_id')
			->andReturn($isFuncReturns);
		return $node->mock();
	}

	/**
	 * Make a (mocked) node object that has a name property that is a \PhpParse\Node\Name with a value of $value
	 *
	 * @param mixed $value	What to set the name string value to
	 *
	 * @return object  An object with an appropriate name property
	 */
	protected function makeNamedNode($value)
	{
		$name =	 Mockery::mock('\PhpParser\Node\Name');
		$name->shouldReceive('__toString')
			->zeroOrMoreTimes()
			->andReturn($value)
			->mock();
		$node = (object)array('name' => $name);
		return $node;
	}

	/**
	 * Evaluate the TestSessionRegenFalse() test
	 *
	 * @param Node $node  The node to evaluate
	 *
	 * @return bool	 The result of calling Tests\TestSessionRegenFalse::evaluate($node)
	 */
	protected function evalTest($node)
	{
		$t = new Tests\TestSessionRegenFalse(null);
		return $t->evaluate($node);
	}
}
