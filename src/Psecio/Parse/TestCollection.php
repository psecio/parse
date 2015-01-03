<?php

namespace Psecio\Parse;

use Countable;
use IteratorAggregate;
use ArrayIterator;

class TestCollection implements Countable, IteratorAggregate
{
    /**
     * @var TestInterface[] Test collection
     */
    private $tests = [];

    /**
     * Load tests into collection
     *
     * @param TestInterface[] $tests
     */
    public function __construct(array $tests = array())
    {
        foreach ($tests as $test) {
            $this->add($test);
        }
    }

    /**
     * Return a count of the current test set
     *
     * @return integer Count result
     */
    public function count()
    {
        return count($this->tests);
    }

    /**
     * Get iterator for test set
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->tests);
    }

    /**
     * Add an test to collection
     *
     * @param  TestInterface $test
     * @return void
     */
    public function add(TestInterface $test)
    {
        $this->tests[$test->getName()] = $test;
    }

    /**
     * Remove an item from the collection
     *
     * @param  string $name Name of test to remove
     * @return void
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->tests)) {
            unset($this->tests[$name]);
        }
    }

    /**
     * Return the current data as an array
     *
     * @return array Current data
     */
    public function toArray()
    {
        return $this->tests;
    }
}
