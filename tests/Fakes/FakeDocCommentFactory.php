<?php

namespace Psecio\Parse\Fakes;

use Psecio\Parse\DocComment\DocCommentFactoryInterface;

class FakeDocCommentFactory implements DocCommentFactoryInterface
{
    protected $commentList = [];

    /**
     * Create a new DocCommentInterface object
     *
     * @param string $comment  A comment string to parse
     *
     * @return DocCommentInterface  The constructed object
     */
    public function createDocComment($comment)
    {
        return $this->commentList[$comment];
    }

    /**
     * Add a doc comment from a given comment to be parsed
     *
     * @param string $comment
     * @param string $docComment
     */
    public function addDocComment($comment, $docComment)
    {
        $this->commentList[$comment] = $docComment;
    }
}
