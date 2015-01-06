<?php

namespace Psecio\Parse;

abstract class Test
{
    /**
     * @var string Default error level for test
     */
    protected $level = 'INFO';

    /**
     * @var string Test description/summary
     */
    protected $description = '';

    /**
     * Get the current error level
     *
     * @return string Error level string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set the error level for the test
     *
     * @param string $level Error level string
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Get the curernt test's description
     *
     * @return string Description information
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description of the current test
     *
     * @param string $description Test description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get test name
     *
     * @return string
     */
    public function getName()
    {
        return preg_replace('/^.+\\\\/', '', get_class($this));
    }

    /**
     * Evaluation method to be called to execute the test
     *
     * @param \Parse\Node $node Node instance
     * @param \Parse\File $file File instance
     * @return boolean Pass/fail of the evaluation
     */
    abstract public function evaluate($node, $file = null);


    /**
     * Determine if $node is a boolean literal, optionally testing for a specific value
     *
     * If $value is true or false, check if $node is specifically $value.
     *
     * @param  \PhpParser\Node $node Node to evaulate
     * @param  bool|null $value Value to check for. Don't check if null.
     * @return bool True if a boolean literal and $value is matched
     */
    protected function nodeIsBoolLiteral(\PhpParser\Node $node, $value = null)
    {
        if ($node->name instanceof \PhpParser\Node\Name) {
            $name = strtolower($node->name);
            if ($name === 'true' || $name === 'false') {
                if ($value === true) {
                    return $name === 'true';
                } elseif ($value === false) {
                    return $name === 'false';
                }
                return true;
            }
        }
        return false;
    }
}
