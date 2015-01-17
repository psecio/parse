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

    public function testDottedOutput()
    {
        $this->assertRegExp(
            '/\./',
            $this->executeCommand(['--format' => 'dots']),
            'Using --format=dots should generate output'
        );
    }

    public function testProgressOutput()
    {
        $this->assertRegExp(
            '/\[\=+\]/',
            $this->executeCommand(['--format' => 'progress'], ['decorated' => true]),
            'Using --format=progress should use the progressbar'
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

    public function testParseCsv()
    {
        $this->assertSame(
            ['php', 'phps'],
            (new ScanCommand)->parseCsv('php,phps'),
            'parsing comma separated values should work'
        );
        $this->assertSame(
            ['php', 'phps'],
            array_values((new ScanCommand)->parseCsv('php,,phps')),
            'multiple commas should be skipped while parsing csv'
        );
        $this->assertSame(
            [],
            (new ScanCommand)->parseCsv(''),
            'parsing an empty string should return an empty array'
        );
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
