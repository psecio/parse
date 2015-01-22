<?php

namespace Psecio\Parse\DocComment;

class DocCommentFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDocComment()
    {
        $comment = <<<EOD
/**
 * Summary
 */
EOD;
        $factory = new DocCommentFactory;
        $docComment = $factory->createDocComment($comment);
        $this->assertSame($comment, $docComment->getRawComment());
    }
}
