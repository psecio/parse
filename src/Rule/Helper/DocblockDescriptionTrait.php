<?php

namespace Psecio\Parse\Rule\Helper;

use Eloquent\Blox\BloxParser;
use ReflectionClass;

/**
 * Helper that reads description and long description from class level docblock
 */
trait DocblockDescriptionTrait
{
    /**
     * @var \Eloquent\Blox\Element\DocumentationBlock The parsed doc comment
     */
    private $docblock;

    /**
     * Read and parse class level doc comment
     *
     * @return \Eloquent\Blox\Element\DocumentationBlock
     */
    private function getDocblock()
    {
        if (!isset($this->docblock)) {
            $this->docblock = (new BloxParser)->parseBlockComment(
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
        return $this->getDocblock()->summary();
    }

    /**
     * Returns the body of the class level doc comment
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->getDocblock()->body();
    }
}
