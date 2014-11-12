<?php

namespace Psecio\Parse;

class TestCollection implements \Countable, \Iterator
{
    /**
     * Current set of data for collection
     * @var array
     */
    private $data = array();

    /**
     * Current position in data (used in Iterator)
     * @var integer
     */
    private $position = 0;

    public function __construct($testSet, $logger)
    {
    	$tests = array();
    	foreach ($testSet as $test) {
    		$testNs = "\\Psecio\\Parse\\Tests\\".$test['name'];
    		$tests[] = new $testNs($logger);
    	}
    	$this->data = $tests;
    }

    // For Countable interface
    /**
     * Return a count of the current data
     *
     * @return integer Count result
     */
    public function count()
    {
        return count($this->data);
    }

    // For Iterator
    /**
     * Return the current item in the set
     *
     * @return mixed Current data item
     */
    public function current()
    {
        return $this->data[$this->position];
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
        return isset($this->data[$this->position]);
    }

    /**
     * Add an item to the collection
     *
     * @param mixed $data Data item to add
     */
    public function add($data)
    {
        $this->data[] = $data;
    }

    /**
     * Remove an item from the collection by index ID
     *
     * @param integer $dataId Item ID
     */
    public function remove($dataId)
    {
        if (array_key_exists($dataId, $this->data)) {
            unset($this->data[$dataId]);
        }
    }

    /**
     * Return the current data as an array
     *
     * @return array Current data
     */
    public function toArray()
    {
		return $this->data;
    }
}