<?php

namespace Psecio\Parse;

/**
 * @covers \Psecio\Parse\RuleFactory
 */
class RuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBundledRules()
    {
        $rules = (new RuleFactory)->createRuleCollection();

        $this->assertTrue(
            $rules->has('EvalFunction'),
            'Collection should include the EvalFunction rule'
        );

        $this->assertTrue(
            $rules->has('ExitOrDie'),
            'Collection should include the ExitOrDie rule'
        );
    }

    public function testIncludeFilter()
    {
        $rules = (new RuleFactory(['evalfunction']))->createRuleCollection();

        $this->assertTrue(
            $rules->has('EvalFunction'),
            'Filtered collection should include the EvalFunction rule'
        );

        $this->assertFalse(
            $rules->has('ExitOrDie'),
            'Filtered collection should NOT include the ExitOrDie rule'
        );
    }

    public function testExcludeFilter()
    {
        $rules = (new RuleFactory([], ['evalfunction']))->createRuleCollection();

        $this->assertFalse(
            $rules->has('EvalFunction'),
            'Filtered collection should NOT include the EvalFunction rule'
        );

        $this->assertTrue(
            $rules->has('ExitOrDie'),
            'Filtered collection should include the ExitOrDie rule'
        );
    }
}
