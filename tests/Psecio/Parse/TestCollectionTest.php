<?php

namespace Psecio\Parse;

require_once 'TestStub.php';

class TestCollectionTest extends \PHPUnit_Framework_TestCase
{
	private $collection;
	private $tests = array(
		array(
			'path' => 'foobar',
			'name' => 'TestStub'
		)
	);
	private $logger = null;

	public function setUp()
	{
		$tests = array();
		$logger = null;
		$this->collection = new TestCollection($tests, $logger);
	}
	public function tearDown()
	{
		unset($this->collection);
	}

	/**
	 * Init the collection and verify
	 *
	 * @covers \Psecio\Parse\TestCollection::__construct
	 * @covers \Psecio\Parse\TestCollection::count
	 */
	public function testInitCollection()
	{
		$collection = new TestCollection($this->tests, $this->logger);
		$tests = $collection->toArray();

		$this->assertTrue($tests[0] instanceof \Psecio\Parse\Tests\TestStub);
	}

	/**
	 * Test that the toArray method returns the right results
	 *
	 * @covers \Psecio\Parse\TestCollection::__construct
	 * @covers \Psecio\Parse\TestCollection::count
	 * @covers \Psecio\Parse\TestCollection::toArray
	 */
	public function testCollectionToArray()
	{
		$collection = new TestCollection($this->tests, $this->logger);
		$tests = $collection->toArray();

		$results = array(
			new \Psecio\Parse\Tests\TestStub(null)
		);

		$this->assertTrue(is_array($tests));
		$this->assertCount(1, $tests);
		$this->assertEquals(1, count($collection));
		$this->assertEquals($tests, $results);
	}

	/**
	 * Test adding a new item to the collection
	 *
	 * @covers \Psecio\Parse\TestCollection::add
	 */
	public function testAddToCollection()
	{
		$collection = new TestCollection($this->tests, $this->logger);

		$newTest = new \Psecio\Parse\Tests\TestStub(null);
		$collection->add($newTest);

		$this->assertCount(2, $collection->toArray());
	}

	/**
	 * Test the removal of a test from the collection
	 *
	 * @covers \Psecio\Parse\TestCollection::add
	 * @covers \Psecio\Parse\TestCollection::remove
	 */
	public function testRemoveFromCollection()
	{
		$collection = new TestCollection($this->tests, $this->logger);

		$newTest = new \Psecio\Parse\Tests\TestStub(null);
		$collection->add($newTest);

		$this->assertCount(2, $collection->toArray());
		$collection->remove(1);

		$this->assertCount(1, $collection->toArray());
	}

	/**
	 * Test to ensure that the iteration of the collection works correctly
	 *
	 * @covers \Psecio\Parse\TestCollection::current
	 * @covers \Psecio\Parse\TestCollection::next
	 * @covers \Psecio\Parse\TestCollection::rewind
	 * @covers \Psecio\Parse\TestCollection::valid
	 * @covers \Psecio\Parse\TestCollection::key
	 */
	public function testIterateAllCollection()
	{
		$tests = array(
			array('path' => 'foobar', 'name' => 'TestStub'),
			array('path' => 'foobar', 'name' => 'TestStub'),
			array('path' => 'foobar', 'name' => 'TestStub')
		);
		$collection = new TestCollection($tests, $this->logger);

		$count = 0;
		foreach ($collection as $test) {
			if ($test instanceof \Psecio\Parse\Tests\TestStub) {
				$count++;
			}
		}
		$this->assertEquals($count, 3);
	}
}