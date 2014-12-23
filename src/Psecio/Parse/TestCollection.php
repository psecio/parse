<?php

namespace Psecio\Parse;

class TestCollection implements \Countable, \Iterator
{
    /**
     * @var Test[] Test collection
     */
    private $tests = array();

    /**
     * @var integer Current position (used in Iterator)
     */
    private $position = 0;

    /**
     * Load tests into collection
     *
     * @param array $testSet
     */
    public function __construct(array $testSet)
    {
    	foreach ($testSet as $test) {
    		$testName = "\\Psecio\\Parse\\Tests\\".$test['name'];
    		$this->add(new $testName());
    	}
    }

    // For Countable interface
    /**
     * Return a count of the current data
     *
     * @return integer Count result
     */
    public function count()
    {
        return count($this->tests);
    }

    // For Iterator
    /**
     * Return the current item in the set
     *
     * @return mixed Current data item
     */
    public function current()
    {
        return $this->tests[$this->position];
    }

    /**
     * Return the current key (position) value
     *
     * @return integer Position value
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Get the next position value
     *
     * @return integer Next position
     */
    public function next()
    {
        return ++$this->position;
    }

    /**
     * Rewind to the beginning of the set (position = 0)
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * See if the requested position exists in the data
     *
     * @return boolean Exists/doesn't exist
     */
    public function valid()
    {
        return isset($this->tests[$this->position]);
    }

    /**
     * Add an test to collection
     *
     * @param Test $test
     */
    public function add(Test $test)
    {
        $this->tests[] = $test;
    }

    /**
     * Remove an item from the collection by index ID
     *
     * @param integer $dataId Item ID
     */
    public function remove($dataId)
    {
        if (array_key_exists($dataId, $this->tests)) {
            unset($this->tests[$dataId]);
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
