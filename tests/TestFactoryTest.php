<?php

namespace Psecio\Parse;

class TestFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBundledTests()
    {
        $tests = (new TestFactory)->createTestCollection()->toArray();

        $this->assertTrue(
            array_key_exists('TestEval', $tests),
            'Collection should include the TestEval test'
        );

        $this->assertTrue(
            array_key_exists('TestExitOrDie', $tests),
            'Collection should include the TestExitOrDie test'
        );
    }

    public function testIncludeFilter()
    {
        $tests = (new TestFactory(['TestEval']))->createTestCollection()->toArray();

        $this->assertTrue(
            array_key_exists('TestEval', $tests),
            'Filtered collection should include the TestEval test'
        );

        $this->assertFalse(
            array_key_exists('TestExitOrDie', $tests),
            'Filtered collection should NOT include the TestExitOrDie test'
        );
    }

    public function testExcludeFilter()
    {
        $tests = (new TestFactory([], ['TestEval']))->createTestCollection()->toArray();

        $this->assertFalse(
            array_key_exists('TestEval', $tests),
            'Filtered collection should NOT include the TestEval test'
        );

        $this->assertTrue(
            array_key_exists('TestExitOrDie', $tests),
            'Filtered collection should include the TestExitOrDie test'
        );
    }
}
