<?php

namespace Psecio\Parse;

use SplFileInfo;
use Mockery as m;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnInvalidPath()
    {
        $this->setExpectedException('RuntimeException');
        new File(new SplFileInfo('this/really/does/not/exist/at/all'));
    }

    public function testGetPath()
    {
        $this->assertEquals(
            __FILE__,
            (new File(new SplFileInfo(__FILE__)))->getPath(),
            'The correct path should be returned'
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

    public function testFetchLines()
    {
        $filename = tempnam(sys_get_temp_dir(), 'psecio-parse-');
        file_put_contents($filename, "line 1\nline 2\nline 3\nline 4");

        $file = new File(new SplFileInfo($filename));

        $this->assertSame(
            ["line 2"],
            $file->fetchLines(2),
            'A single argument to fetchLines should fetch only one line'
        );

        $this->assertSame(
            ["line 2", "line 3", "line 4"],
            $file->fetchLines(2, 4),
            'Two arguments to fetchLines should fetch the complete series of lines'
        );

        $this->assertSame(
            ["line 3"],
            $file->fetchLines(3, 3),
            'Specifying the same line twice should grab that line'
        );

        $this->assertSame(
            ["line 1", "line 2"],
            $file->fetchNode(
                m::mock('PhpParser\Node')
                    ->shouldReceive('getAttributes')
                    ->once()
                    ->andReturn(['startLine' => 1, 'endLine' => 2])
                    ->mock()
            ),
            'fetchNode should fetch based on node attributes'
        );

        unlink($filename);
    }
}
