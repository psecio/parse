<?php

namespace Psecio\Parse;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use RuntimeException;

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
        $this->rules[strtolower($rule->getName())] = $rule;
    }

    /**
     * Check if rule exist in collection
     *
     * @param  string $name Name of rule
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists(strtolower($name), $this->rules);
    }

    /**
     * Get rule from collection
     *
     * @param  string $name Name of rule
     * @return RuleInterface
     * @throws RuntimeException If rule does not exist
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->rules[strtolower($name)];
        }
        throw new RuntimeException("The rule $name does not exist");
    }

    /**
     * Remove an item from the collection
     *
     * @param  string $name Name of rule
     * @return void
     * @throws RuntimeException If rule does not exist
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->rules[strtolower($name)]);
            return;
        }
        throw new RuntimeException("The rule $name does not exist");
    }

    /**
     * Return the collection an array
     *
     * @return array Current data
     */
    public function toArray()
    {
        return $this->rules;
    }
}
