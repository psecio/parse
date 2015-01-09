<?php

namespace Psecio\Parse;

use ArrayIterator;
use DirectoryIterator;

/**
 * The TestFactory is responsible for creating a TestCollection with the bundled set of tests
 */
class TestFactory
{
    /**
     * @var TestInterface[] Current test set
     */
    private $tests;

    /**
     * Register include and exclude rules
     *
     * @param string[] $include Names of tests to include in collection (no value means all tests)
     * @param string[] $exclude Names of tests to exclude from collection
     */
    public function __construct(array $include = array(), array $exclude = array())
    {
        $this->tests = $this->includeFilter(
            $this->excludeFilter(
                $this->getBundledTests(__DIR__ . '/Tests'),
                $exclude
            ),
            $include
        );
    }

    /**
     * Create collection of tests
     *
     * @return TestCollection
     */
    public function createTestCollection()
    {
        return new TestCollection($this->tests);
    }

    /**
     * Get tests included in source
     *
     * @param  string $testdir Directory to scan for tests
     * @return TestInterface[]
     */
    private function getBundledTests($testdir)
    {
        $tests = [];

        foreach (new DirectoryIterator($testdir) as $splFileInfo) {
            if ($splFileInfo->isDot() || $splFileInfo->isDir()) {
                continue;
            }
            $className = "\\Psecio\\Parse\\Tests\\{$splFileInfo->getBasename('.php')}";
            $tests[] = new $className;
        }

        return $tests;
    }

    /**
     * Apply include filter
     *
     * @param  TestInterface[] $tests Current test set
     * @param  string[]        $include Names of tests to include in collection
     * @return TestInterface[] Filtered test set
     */
    private function includeFilter(array $tests, array $include)
    {
        return array_filter(
            $tests,
            function (TestInterface $test) use ($include) {
                return empty($include) || in_array($test->getName(), $include);
            }
        );
    }

    /**
     * Apply exclude filter
     *
     * @param  TestInterface[] $tests Current test set
     * @param  string[]        $exclude Names of tests to exclude from collection
     * @return TestInterface[] Filtered test set
     */
    private function excludeFilter(array $tests, array $exclude)
    {
        return array_filter(
            $tests,
            function (TestInterface $test) use ($exclude) {
                return !in_array($test->getName(), $exclude);
            }
        );
    }
}
