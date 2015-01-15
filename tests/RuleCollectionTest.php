<?php

namespace Psecio\Parse;

use Mockery as m;

class RuleCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Psecio\Parse\RuleCollection::__construct
     * @covers \Psecio\Parse\RuleCollection::toArray
     */
    public function testToArray()
    {
        $rule = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn('RuleName')
            ->mock();

        $this->assertSame(
            (new RuleCollection([$rule]))->toArray(),
            ['RuleName' => $rule],
            'toArray() should return the correct array'
        );
    }

    /**
     * @covers \Psecio\Parse\RuleCollection::count
     * @covers \Psecio\Parse\RuleCollection::add
     * @covers \Psecio\Parse\RuleCollection::remove
     */
    public function testCountable()
    {
        $collection = new RuleCollection;

        $this->assertCount(
            0,
            $collection,
            'An empty collection should count to 0'
        );

        $collection->add(
            m::mock('\Psecio\Parse\RuleInterface')
                ->shouldReceive('getName')
                ->andReturn('RuleName')
                ->mock()
        );

        $this->assertCount(
            1,
            $collection,
            '1 added item should be reflected in count'
        );

        $collection->remove('RuleName');

        $this->assertCount(
            0,
            $collection,
            '1 removed item should be reflected in count'
        );
    }

    /**
     * @covers \Psecio\Parse\RuleCollection::getIterator
     */
    public function testIterator()
    {
        $rule = m::mock('\Psecio\Parse\RuleInterface')
            ->shouldReceive('getName')
            ->andReturn('RuleName')
            ->mock();

        $this->assertSame(
            iterator_to_array(new RuleCollection([$rule])),
            ['RuleName' => $rule],
            'iteration of the collection should work correctly'
        );
    }
}
