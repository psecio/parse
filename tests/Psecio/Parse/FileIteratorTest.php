<?php

namespace Psecio\Parse;

class FileIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIterator()
    {
        // Create array of files in Subscriber and Tests subdirs
        $files = iterator_to_array(
            new FileIterator([__DIR__.'/Subscriber', __DIR__.'/Tests'])
        );

        $this->assertTrue(
            array_key_exists(__DIR__.'/Subscriber/ConsoleStandardTest.php', $files),
            'ConsoleStandardTest should be found in Subscriber subdir'
        );

        $this->assertTrue(
            array_key_exists(__DIR__.'/Tests/TestSessionRegenFalseTest.php', $files),
            'TestSessionRegenFalseTest should be found in Tests subdir'
        );
    }

    public function testCountable()
    {
        $this->assertTrue(
            count(new FileIterator([__DIR__.'/Subscriber'])) > 2,
            'The Subscriber tests subdir contains more than 2 files'
        );
    }
}
