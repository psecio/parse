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
    /**
     * @var string Name of empty php file used in scanning
     */
    private static $filename;

    public static function setUpBeforeClass()
    {
        self::$filename = sys_get_temp_dir() . '/' . uniqid('psecio-parse') . '.php';
        touch(self::$filename);
    }

    public static function tearDownAfterClass()
    {
        unlink(self::$filename);
    }

    /**
     * @param  array  $input   Input data using when executing command
     * @param  array  $options Options used then executing command
     * @return string The generated output
     */
    private function executeCommand(array $input, array $options = array())
    {
        $application = new Application;
        $application->add(new ScanCommand);
        $tester = new CommandTester($application->find('scan'));
        $tester->execute($input, $options);
        return $tester->getDisplay();
    }

    public function testConsoleOutput()
    {
        $this->assertRegExp(
            '/Parse: A PHP Security Scanner/',
            $this->executeCommand(
                [
                    'command' => 'scan',
                    'path' => [self::$filename],
                    '--format' => 'txt'
                ]
            ),
            'Using --format=txt should generate output'
        );
    }

    public function testVerboseOutput()
    {
        $this->assertRegExp(
            '/\[PARSE\]/',
            $this->executeCommand(
                [
                    'command' => 'scan',
                    'path' => [self::$filename]
                ],
                ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]
            ),
            'Using -v should generate verbose output'
        );
    }

    public function testVeryVerboseOutput()
    {
        $this->assertRegExp(
            '/\[DEBUG\]/',
            $this->executeCommand(
                [
                    'command' => 'scan',
                    'path' => [self::$filename]
                ],
                ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]
            ),
            'Using -vv should generate debug output'
        );
    }

    public function testXmlOutput()
    {
        $this->assertRegExp(
            '/^<\?xml version="1.0" encoding="UTF-8"\?>/',
            $this->executeCommand(
                [
                    'command' => 'scan',
                    'path' => [self::$filename],
                    '--format' => 'xml'
                ]
            ),
            'Using --format=xml should generate a valid xml doctype'
        );
    }

    public function testExceptionOnUnknownFormat()
    {
        $this->setExpectedException('RuntimeException');
        $this->executeCommand(
            [
                'command' => 'scan',
                'path' => [self::$filename],
                '--format' => 'this-format-does-not-exist'
            ]
        );
    }
}
