<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Psecio\Parse\Command\ListTestsCommand
 */
class ListTestsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application;
        $application->add(new ListTestsCommand);

        $command = $application->find('list-tests');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp(
            '/Searching for tests/',
            $commandTester->getDisplay(),
            'The list-tests command should produce output'
        );
    }
}
