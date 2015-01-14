<?php

namespace Psecio\Parse;

/**
 * @covers \Psecio\Parse\FileIterator
 */
class FileIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string[] List of file names to find in scan
     */
    private static $expectedFiles = [];

    /**
     * @var string Directory used to set up test files
     */
    private static $testDir;

    public static function expand($name)
    {
        return self::$testDir . '/' . $name;
    }

    public static function setUpBeforeClass()
    {
        self::$testDir = sys_get_temp_dir() . '/' . uniqid('psecio-parse');
        mkdir(self::$testDir);
        mkdir(self::expand('dir'));
        mkdir(self::expand('dir2'));
        touch(self::expand('dir/file'));
        touch(self::expand('dir2/file'));
        self::$expectedFiles[] = self::expand('dir/file');
        self::$expectedFiles[] = self::expand('dir2/file');
    }

    public static function tearDownAfterClass()
    {
        unlink(self::expand('dir/file'));
        unlink(self::expand('dir2/file'));
        rmdir(self::expand('dir'));
        rmdir(self::expand('dir2'));
        rmdir(self::$testDir);
    }

    public function testDirectoryPath()
    {
        $foundFiles = iterator_to_array(new FileIterator([self::$testDir]));
        foreach (self::$expectedFiles as $filename) {
            $this->assertArrayHasKey(
                $filename,
                $foundFiles,
                "FileIterator should find $filename"
            );
        }
    }

    public function testFilenamePath()
    {
        $this->assertArrayHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__FILE__])),
            __FILE__ . ' should be found in iterator'
        );
    }

    public function testIgnoreFilename()
    {
        $this->assertEmpty(
            iterator_to_array(new FileIterator([__FILE__], [__FILE__])),
            __FILE__ . ' should be ignored'
        );
    }

    public function testIgnoreDirectory()
    {
        $this->assertEmpty(
            iterator_to_array(new FileIterator([__FILE__], [__DIR__])),
            __FILE__ . ' should be ignored when ' . __DIR__ . ' is ignored'
        );
    }

    public function testIgnoreNonCompletePaths()
    {
        $expected = self::expand('dir2/file');
        $this->assertArrayHasKey(
            $expected,
            iterator_to_array(new FileIterator(self::$expectedFiles, [self::expand('dir')])),
            "'$expected' should not be ignored as 'dir' should not match 'dir2'"
        );
    }

    public function testInvalidIgnorePath()
    {
        $this->assertArrayHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__FILE__], ['this/really/does/not/exist'])),
            'Adding a non existing path to the ignore list should not affect anything'
        );
    }

    public function testCountable()
    {
        $this->assertSame(
            count(self::$expectedFiles),
            count(new FileIterator([self::$testDir])),
            self::$testDir . 'should contain the correct number of test files'
        );
    }
}
