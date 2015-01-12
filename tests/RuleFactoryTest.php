<?php

namespace Psecio\Parse;

class RuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBundledRules()
    {
        $rules = (new RuleFactory)->createRuleCollection()->toArray();

        $this->assertTrue(
            array_key_exists('TestEval', $rules),
            'Collection should include the TestEval rule'
        );

        $this->assertTrue(
            array_key_exists('TestExitOrDie', $rules),
            'Collection should include the TestExitOrDie rule'
        );
    }

    public function testIncludeFilter()
    {
        $rules = (new RuleFactory(['TestEval']))->createRuleCollection()->toArray();

        $this->assertTrue(
            array_key_exists('TestEval', $rules),
            'Filtered collection should include the TestEval rule'
        );

        $this->assertFalse(
            array_key_exists('TestExitOrDie', $rules),
            'Filtered collection should NOT include the TestExitOrDie rule'
        );
    }

    public function testExcludeFilter()
    {
        $rules = (new RuleFactory([], ['TestEval']))->createRuleCollection()->toArray();

        $this->assertFalse(
            array_key_exists('TestEval', $rules),
            'Filtered collection should NOT include the TestEval rule'
        );

        $this->assertTrue(
            array_key_exists('TestExitOrDie', $rules),
            'Filtered collection should include the TestExitOrDie rule'
        );
    }
}
