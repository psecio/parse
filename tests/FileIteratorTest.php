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
        touch(self::expand('dir/file.php'));
        touch(self::expand('dir2/file.php'));
        self::$expectedFiles[] = realpath(self::expand('dir/file.php'));
        self::$expectedFiles[] = realpath(self::expand('dir2/file.php'));
    }

    public static function tearDownAfterClass()
    {
        unlink(self::expand('dir/file.php'));
        unlink(self::expand('dir2/file.php'));
        rmdir(self::expand('dir'));
        rmdir(self::expand('dir2'));
        rmdir(self::$testDir);
    }

    public function testExceptionWhenNoPathsAreSet()
    {
        $this->setExpectedException('RuntimeException');
        new FileIterator([]);
    }

    /**
     * Test that the directory and files were created correctly and
     * the iterator contains them
     */
    public function testDirectoryPath()
    {
        $iterator = new FileIterator([self::$testDir]);
        $paths = $iterator->getPaths();
        
        $this->assertCount(0, array_diff($paths, self::$expectedFiles));
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
        $this->assertArrayHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__FILE__], [__FILE__])),
            __FILE__ . ' should not be ignored as ignore filters should not matter when files are added'
        );
        $this->assertArrayNotHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__DIR__], [__FILE__])),
            __FILE__ . ' should be ignored when ' . __DIR__ . ' is ignored'
        );
    }

    public function testIgnoreDirectory()
    {
        $this->assertEmpty(
            iterator_to_array(new FileIterator([__DIR__], [__DIR__])),
            'All files in dir should be ignored when ' . __DIR__ . ' is ignored'
        );
    }

    /**
     * @group single
     */
    public function testIgnoreNonCompletePaths()
    {
        $expected = realpath(self::expand('dir2/file.php'));
        $iterator = new FileIterator(self::$expectedFiles, [self::expand('dir')]);

        $this->assertArrayHasKey(
            $expected,
            $iterator->toArray(),
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

    public function testFileExtensions()
    {
        $this->assertArrayNotHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__DIR__], [], ['txt'])),
            __FILE__ . ' should be ignored as it does not have a .txt extension'
        );
        $this->assertArrayHasKey(
            __FILE__,
            iterator_to_array(new FileIterator([__FILE__], [], ['txt'])),
            __FILE__ . ' should be included as extensions should not matter when a file is added'
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
