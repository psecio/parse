<?php

namespace Psecio\Parse;

/**
 * @coversNothing
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    const EXECUTABLE = "bin/psecio-parse";

    /**
     * This test concerns issue #58
     * It will only fail if xdebug is enabled
     */
    public function testMaxNestingLevel()
    {
        $filename = sys_get_temp_dir() . '/' . uniqid('psecio-parse') . '.php';

        file_put_contents(
            $filename,
            '<?php $a = "foo"' . implode('', array_fill(0, 200, '."bar"')) . ';'
        );

        pclose(popen(self::EXECUTABLE . " scan $filename", 'r'));
        unlink($filename);
    }
}
