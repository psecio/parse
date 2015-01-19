<?php

namespace Psecio\Parse;

use Mockery as m;

/**
 * @covers \Psecio\Parse\RuleCollection
 */
class RuleCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddHasGetRemove()
    {
        $rule = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn('name')
            ->mock();

        $collection = new RuleCollection;

        $this->assertCount(
            0,
            $collection,
            'An empty collection should count to 0'
        );

        $this->assertFalse(
            $collection->has('name'),
            'Collection should not contain the name rule'
        );

        $collection->add($rule);

        $this->assertCount(
            1,
            $collection,
            '1 added item should be reflected in count'
        );

        $this->assertTrue(
            $collection->has('name'),
            'The name rule was added and should be contained'
        );

        $this->assertSame(
            $rule,
            $collection->get('name'),
            'The named rule should be returned'
        );

        $collection->remove('name');

        $this->assertCount(
            0,
            $collection,
            '1 removed item should be reflected in count'
        );

        $this->assertFalse(
            $collection->has('name'),
            'Rule was removed and should not be contained'
        );
    }

    public function testCaseInsensitivity()
    {
        $collection = new RuleCollection;

        $collection->add(
            m::mock('\Psecio\Parse\RuleInterface')
                ->shouldReceive('getName')
                ->andReturn('name')
                ->mock()
        );

        $this->assertTrue(
            $collection->has('NAME'),
            'name should be accessible independent of case'
        );

        $this->assertSame(
            $collection->get('NAme'),
            $collection->get('naME'),
            'getting a rule should be case insensitive'
        );
    }

    public function testIteratorAndArray()
    {
        $rule = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn('name')
            ->mock();

        $this->assertSame(
            iterator_to_array(new RuleCollection([$rule])),
            ['name' => $rule],
            'iteration of the collection should work correctly'
        );

        $this->assertSame(
            (new RuleCollection([$rule]))->toArray(),
            ['name' => $rule],
            'toArray() should return the correct array'
        );
    }

    public function testExceptionInGet()
    {
        $this->setExpectedException('RuntimeException');
        (new RuleCollection)->get('does-not-exist');
    }

    public function testExceptionInRemove()
    {
        $this->setExpectedException('RuntimeException');
        (new RuleCollection)->remove('does-not-exist');
    }
}
