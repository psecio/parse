<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Psecio\Parse\Command\RulesCommand
 */
class RulesCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testListRules()
    {
        $application = new Application;
        $application->add(new RulesCommand);
        $command = $application->find('rules');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp(
            '/Description/i',
            $commandTester->getDisplay(),
            'The rules command should produce output'
        );
    }

    public function testDescribeRule()
    {
        $application = new Application;
        $application->add(new RulesCommand);
        $command = $application->find('rules');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['command' => $command->getName(), 'rule' => 'exitordie']);

        $this->assertRegExp(
            '/ExitOrDie/',
            $commandTester->getDisplay(),
            'The rules command should produce output'
        );
    }
}
