<?php

namespace Psecio\Parse\Rule\Helper;

use Psecio\Parse\DocComment\DocComment;
use ReflectionClass;

/**
 * Helper that reads description and long description from class level docblock
 */
trait DocblockDescriptionTrait
{
    /**
     * @var \Psecio\Parse\DocComment\DocComment The parsed doc comment
     */
    private $docblock;

    /**
     * Read and parse class level doc comment
     *
     * @return \Psecio\Parse\DocComment\DocComment
     */
    private function getDocblock()
    {
        if (!isset($this->docblock)) {
            $this->docblock = new DocComment(
                (new ReflectionClass($this))->getDocComment()
            );
        }

        return $this->docblock;
    }

    /**
     * Returns the summary of the class level doc comment
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getDocblock()->getSummary();
    }

    /**
     * Returns the body of the class level doc comment
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->getDocblock()->getBody();
    }
}
