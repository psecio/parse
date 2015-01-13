<?php

namespace Psecio\Parse;

use Countable;
use IteratorAggregate;
use ArrayIterator;

/**
 * Responsible for handling the ruleset
 */
class RuleCollection implements Countable, IteratorAggregate
{
    /**
     * @var RuleInterface[] Registered rules
     */
    private $rules = [];

    /**
     * Load rules into collection
     *
     * @param RuleInterface[] $rules
     */
    public function __construct(array $rules = array())
    {
        foreach ($rules as $rule) {
            $this->add($rule);
        }
    }

    /**
     * Return a count of the current ruleset
     *
     * @return integer Count result
     */
    public function count()
    {
        return count($this->rules);
    }

    /**
     * Get iterator for ruleset
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->rules);
    }

    /**
     * Add an rule to collection
     *
     * @param  RuleInterface $rule
     * @return void
     */
    public function add(RuleInterface $rule)
    {
        $this->rules[$rule->getName()] = $rule;
    }

    /**
     * Remove an item from the collection
     *
     * @param  string $name Name of rule to remove
     * @return void
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->rules)) {
            unset($this->rules[$name]);
        }
    }

    /**
     * Return the current data as an array
     *
     * @return array Current data
     */
    public function toArray()
    {
        return $this->rules;
    }
}
