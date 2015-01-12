<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Psecio\Parse\Command\InfoCommand
 */
class InfoCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application;
        $application->add(new InfoCommand);

        $command = $application->find('info');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp(
            '/Searching for rules/',
            $commandTester->getDisplay(),
            'The info command should produce output'
        );
    }
}
