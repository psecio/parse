<?php

namespace Psecio\Parse;

use Mockery as m;

class TestSessionRegenFalseTest extends \PHPUnit_Framework_TestCase
{
    public function testNotAFunction()
    {
        $this->assertTrue(
            (new Tests\TestSessionRegenFalse(null))->evaluate(
                m::mock('\PhpParser\Node'),
                m::mock('\Psecio\Parse\File')
            ),
            "\\PhpParer\\Node does not represent a function hence test should pass"
        );
    }

    public function testNoArgument()
    {
        $node = m::mock('\PhpParser\Node\Expr\FuncCall');
        $node->name = 'session_regenerate_id';
        $node->args = [];

        $this->assertFalse(
            (new Tests\TestSessionRegenFalse(null))->evaluate(
                $node,
                m::mock('\Psecio\Parse\File')
            ),
            "If it's the correct function, and there are no arguments, the test should fail"
        );
    }

    public function testInvalidArgument()
    {
        $node = m::mock('\PhpParser\Node\Expr\FuncCall');
        $node->name = 'session_regenerate_id';
        $node->args = [(object)['value' => $this->makeNamedNode('false')]];

        $this->assertFalse(
            (new Tests\TestSessionRegenFalse(null))->evaluate(
                $node,
                m::mock('\Psecio\Parse\File')
            ),
            "If it's the correct function and the argument is false, the test should fail"
        );
    }

    public function testValidArgument()
    {
        $node = m::mock('\PhpParser\Node\Expr\FuncCall');
        $node->name = 'session_regenerate_id';
        $node->args = [(object)['value' => $this->makeNamedNode('true')]];

        $this->assertTrue(
            (new Tests\TestSessionRegenFalse(null))->evaluate(
                $node,
                m::mock('\Psecio\Parse\File')
            ),
            "If it's the correct function and the argument is true, the test should succeed"
        );
    }

    public function testArgumentNotBoolean()
    {
        $node = m::mock('\PhpParser\Node\Expr\FuncCall');
        $node->name = 'session_regenerate_id';
        $node->args = [(object)['value' => $this->makeNamedNode('notBool')]];

        $this->assertFalse(
            (new Tests\TestSessionRegenFalse(null))->evaluate(
                $node,
                m::mock('\Psecio\Parse\File')
            ),
            "If it's the correct function and the argument is non-boolean, the test should fail"
        );
    }

    /**
     * Make a (mocked) node object that has a name property that is a \PhpParse\Node\Name with a value of $value
     *
     * @param  string $name What to set the name string value to
     * @return \PhpParser\Node With appropriate name property
     */
    protected function makeNamedNode($name)
    {
        $node = m::mock('\PhpParser\Node');
        $node->name = m::mock('\PhpParser\Node\Name')
            ->shouldReceive('__toString')
            ->zeroOrMoreTimes()
            ->andReturn($name)
            ->mock();

        return $node;
    }
}
