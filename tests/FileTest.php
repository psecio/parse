<?php

namespace Psecio\Parse;

use SplFileInfo;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPath()
    {
        $this->assertEquals(
            __FILE__,
            (new File(new SplFileInfo(__FILE__)))->getPath(),
            'The same path that is set should be returned'
        );
    }

    public function testIsPathMatch()
    {
        $this->assertTrue(
            (new File(new SplFileInfo(__FILE__)))->isPathMatch('/.php$/'),
            'Test should pass as the path of __FILE__ ends with .php'
        );
    }

    public function testGetContent()
    {
        $this->assertRegExp(
            '/public function testGetContent()/',
            (new File(new SplFileInfo(__FILE__)))->getContents(),
            'The contents from this file should be fetched correctly'
        );
    }

    public function testSetContent()
    {
        $newContent = 'this is a test';

        $file = new File(new SplFileInfo(__FILE__));
        $file->setContents($newContent);

        $this->assertEquals(
            $file->getContents(),
            $newContent,
            'Setting new content should override filesystem content'
        );
    }

    /**
     * Test the "get lines" funcitonality
     */
    public function testGetLines()
    {
        $newContent = "this is\na test with\nnewlines\nhere";

        $file = new File(new SplFileInfo(__FILE__));
        $file->setContents($newContent);

        // A single line w/o optional param
        $lines = $file->getLines(2);

        $this->assertTrue(
            is_array($lines) && !empty($lines)
        );

        $this->assertSame(
            $lines,
            array('a test with'),
            'Using getLines with one param should yield a single line'
        );

        // Multiple lines with second param
        $this->assertEquals(
            $file->getLines(2, 5),
            array('a test with', 'newlines', 'here'),
            'Using getLines with two param should yield multiple lines'
        );
    }
}
