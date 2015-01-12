<?php

namespace Psecio\Parse;

class RuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBundledRules()
    {
        $rules = (new RuleFactory)->createRuleCollection()->toArray();

        $this->assertTrue(
            array_key_exists('EvalFunction', $rules),
            'Collection should include the EvalFunction rule'
        );

        $this->assertTrue(
            array_key_exists('ExitOrDie', $rules),
            'Collection should include the ExitOrDie rule'
        );
    }

    public function testIncludeFilter()
    {
        $rules = (new RuleFactory(['EvalFunction']))->createRuleCollection()->toArray();

        $this->assertTrue(
            array_key_exists('EvalFunction', $rules),
            'Filtered collection should include the EvalFunction rule'
        );

        $this->assertFalse(
            array_key_exists('ExitOrDie', $rules),
            'Filtered collection should NOT include the ExitOrDie rule'
        );
    }

    public function testExcludeFilter()
    {
        $rules = (new RuleFactory([], ['EvalFunction']))->createRuleCollection()->toArray();

        $this->assertFalse(
            array_key_exists('EvalFunction', $rules),
            'Filtered collection should NOT include the EvalFunction rule'
        );

        $this->assertTrue(
            array_key_exists('ExitOrDie', $rules),
            'Filtered collection should include the ExitOrDie rule'
        );
    }
}
