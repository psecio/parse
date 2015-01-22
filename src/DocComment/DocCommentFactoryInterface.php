<?php

namespace Psecio\Parse\DocComment;

interface DocCommentFactoryInterface
{
    /**
     * Create a new DocCommentInterface object
     *
     * @param string $comment  A comment string to parse
     *
     * @return DocCommentInterface  The constructed object
     */
    public function createDocComment($comment);
}
