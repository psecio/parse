<?php

namespace Psecio\Parse\Fakes;

use Psecio\Parse\DocComment\DocCommentFactoryInterface;

class FakeDocCommentFactory implements DocCommentFactoryInterface
{
    protected $commentList = [];
    
    public function createDocComment($comment)
    {
        return $this->commentList[$comment];
    }

    public function addDocComment($comment, $docComment)
    {
        $this->commentList[$comment] = $docComment;
    }
}
