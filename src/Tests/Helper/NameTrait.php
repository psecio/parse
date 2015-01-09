<?php

namespace Psecio\Parse\Tests\Helper;

/**
 * Helper that defines getName()
 */
trait NameTrait
{
    /**
     * Get test name
     *
     * @return string
     */
    public function getName()
    {
        return preg_replace('/^.+\\\\/', '', get_class($this));
    }
}
