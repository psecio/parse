<?php

namespace Psecio\Parse\DocComment;

class DocCommentFactory implements DocCommentFactoryInterface
{
    /**
     * Create a new DocCommentInterface object
     *
     * @param string $comment  A comment string to parse
     *
     * @return DocCommentInterface  The constructed object
     */
    public function createDocComment($comment)
    {
        return new DocComment($comment);
    }
}
