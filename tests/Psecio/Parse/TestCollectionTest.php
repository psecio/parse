<?php

namespace Psecio\Parse;

use Mockery as m;

class TestCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Psecio\Parse\TestCollection::__construct
     * @covers \Psecio\Parse\TestCollection::toArray
     */
    public function testToArray()
    {
        $test = m::mock('\Psecio\Parse\TestInterface')
            ->shouldReceive('getName')
            ->andReturn('TestName')
            ->mock();

        $this->assertSame(
            (new TestCollection([$test]))->toArray(),
            ['TestName' => $test],
            'toArray() should return the correct array'
        );
    }

    /**
     * @covers \Psecio\Parse\TestCollection::count
     * @covers \Psecio\Parse\TestCollection::add
     * @covers \Psecio\Parse\TestCollection::remove
     */
    public function testCountable()
    {
        $collection = new TestCollection;

        $this->assertCount(
            0,
            $collection,
            'An empty collection should count to 0'
        );

        $collection->add(
            m::mock('\Psecio\Parse\TestInterface')
                ->shouldReceive('getName')
                ->andReturn('TestName')
                ->mock()
        );

        $this->assertCount(
            1,
            $collection,
            '1 added item should be reflected in count'
        );

        $collection->remove('TestName');

        $this->assertCount(
            0,
            $collection,
            '1 removed item should be reflected in count'
        );
    }

    /**
     * @covers \Psecio\Parse\TestCollection::getIterator
     */
    public function testIterator()
    {
        $test = m::mock('\Psecio\Parse\TestInterface')
            ->shouldReceive('getName')
            ->andReturn('TestName')
            ->mock();

        $this->assertSame(
            iterator_to_array(new TestCollection([$test])),
            ['TestName' => $test],
            'iteration of the collection should work correctly'
        );
    }
}
