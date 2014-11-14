<?php

namespace Psecio\Parse;

abstract class Test
{
	/**
	 * Logger instance (Monolog)
	 * @var object
	 */
	private $logger;

	/**
	 * Default error level for test
	 * @var string
	 */
	protected $level = 'INFO';

	/**
	 * Test description/summary
	 * @var string
	 */
	protected $description = '';

	/**
	 * Init the object and set the logger
	 *
	 * @param object $logger Logger instance
	 */
	public function __construct($logger)
	{
		$this->logger = $logger;
	}

	/**
	 * Get the current logger instance
	 *
	 * @return object Logger instance
	 */
	public function getLogger()
	{
		return $this->logger;
	}

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
	 * Evaluation method to be called to execute the test
	 *
	 * @param \Parse\Node $node Node instance
	 * @param \Parse\File $file File instance
	 * @return boolean Pass/fail of the evaluation
	 */
	abstract public function evaluate($node, $file = null);
}