<?php

namespace Psecio\Parse\DocComment;

class DocCommentFactoryTest extends \PHPUnit_Framework_TestCase
{
    const COMMENT = <<<EOD
/**
 * Summary
 */
EOD;

    public function testCreateDocCommentReturnsDocComment()
    {
        $factory = new DocCommentFactory;
        $docComment = $factory->createDocComment(self::COMMENT);
        $this->assertInstanceOf(__NAMESPACE__ . '\\DocCommentInterface', $docComment);
    }

    public function testCreateDocCommentSavesRawComment()
    {
        $factory = new DocCommentFactory;
        $docComment = $factory->createDocComment(self::COMMENT);
        $this->assertSame(self::COMMENT, $docComment->getRawComment());
    }
}
