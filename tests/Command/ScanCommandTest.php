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

    public function testConsoleOutput()
    {
        $this->assertRegExp(
            '/Parse: A PHP Security Scanner/',
            $this->executeCommand(['--format' => 'txt']),
            'Using --format=txt should generate output'
        );
    }

    public function testVerboseOutput()
    {
        $this->assertRegExp(
            '/\[PARSE\]/',
            $this->executeCommand([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]),
            'Using -v should generate verbose output'
        );
    }

    public function testVeryVerboseOutput()
    {
        $this->assertRegExp(
            '/\[DEBUG\]/',
            $this->executeCommand([], ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]),
            'Using -vv should generate debug output'
        );
    }

    public function testXmlOutput()
    {
        $this->assertRegExp(
            '/^<\?xml version="1.0" encoding="UTF-8"\?>/',
            $this->executeCommand(['--format' => 'xml']),
            'Using --format=xml should generate a valid xml doctype'
        );
    }

    public function testExceptionOnUnknownFormat()
    {
        $this->setExpectedException('RuntimeException');
        $this->executeCommand(['--format' => 'this-format-does-not-exist']);
    }

    private function executeCommand(array $input, array $options = array())
    {
        $application = new Application;
        $application->add(new ScanCommand);
        $tester = new CommandTester($application->find('scan'));
        $input['command'] = 'scan';
        $input['path'] = [self::$filename];
        $tester->execute($input, $options);

        return $tester->getDisplay();
    }
}
