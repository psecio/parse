<?php

namespace Psecio\Parse;

class FileIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected static $dirs = ['testFiles/FileIteratorTest1',
                              'testFiles/FileIteratorTest2'];

    protected $fullDirs;

    protected static $expectedFiles = [ [ 'README.md', 'test-2.md', 'test-3.md', 'test-4.md' ],
                                        [ 'README.md', 'test-1.md' ], ];

    public function setUp()
    {
        $this->fullDirs = array_map(
            function($d) { return __DIR__ . DIRECTORY_SEPARATOR . $d; },
            self::$dirs
            );
    }

    public function testIterator()
    {
        // Create array of files in Subscriber and Tests subdirs
        $files = iterator_to_array(new FileIterator($this->fullDirs));

        foreach (self::$dirs as $k => $dirName) {
            $fullPath = $this->fullDirs[$k] . DIRECTORY_SEPARATOR;
            foreach (self::$expectedFiles[$k] as $fileName) {
                $this->assertArrayHasKey($fullPath . $fileName,
                                         $files,
                                         "$fileName should be found in $dirName subdirectory");
            }
        }
    }

    public function testCountable()
    {
        $expected = count(self::$expectedFiles[0]);
        $full = $this->fullDirs[0];
        $sub = self::$dirs[0];
        $this->assertEquals($expected,
                            count(new FileIterator([$full])),
                            "The $sub subdir should contain $expected files"
        );
    }
}
