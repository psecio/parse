<?php

namespace Psecio\Parse\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Psecio\Parse\Command\ScanCommand
 */
class ScanCommandTest extends \PHPUnit_Framework_TestCase
{
    private $application;
    private $command;
    private $commandTester;

    protected function setUp()
    {
        $this->application = new Application;
        $this->application->add(new ScanCommand);
        $this->command = $this->application->find('scan');
        $this->commandTester = new CommandTester($this->command);
    }

    public function testConsoleOutput()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'path' => [__DIR__],
            '--format' => 'txt'
        ]);
        $this->assertRegExp(
            '/Parse: A PHP Security Scanner/',
            $this->commandTester->getDisplay(),
            'Using --format=txt should generate output'
        );
    }

    public function testVerboseOutput()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
                'path' => [__DIR__]
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );
        $this->assertRegExp(
            '/\[PARSE\]/',
            $this->commandTester->getDisplay(),
            'Using -v should generate verbose output'
        );
    }

    public function testVeryVerboseOutput()
    {
        $this->commandTester->execute(
            [
                'command' => $this->command->getName(),
                'path' => [__DIR__]
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE
            ]
        );
        $this->assertRegExp(
            '/\[DEBUG\]/',
            $this->commandTester->getDisplay(),
            'Using -v should generate verbose output'
        );
    }

    public function testXmlOutput()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'path' => [__DIR__],
            '--format' => 'xml'
        ]);
        $this->assertRegExp(
            '/^<\?xml version="1.0" encoding="UTF-8"\?>/',
            $this->commandTester->getDisplay(),
            'Using --format=xml should generate a valid xml doctype'
        );
    }

    public function testExceptionOnUnknownFormat()
    {
        $this->setExpectedException('RuntimeException');
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'path' => [__DIR__],
            '--format' => 'this-format-does-not-exist'
        ]);
    }
}
