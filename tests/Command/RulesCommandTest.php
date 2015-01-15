<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Psecio\Parse\Command\RulesCommand
 */
class RulesCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application;
        $application->add(new RulesCommand);

        $command = $application->find('rules');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp(
            '/Searching for rules/',
            $commandTester->getDisplay(),
            'The rules command should produce output'
        );
    }
}
