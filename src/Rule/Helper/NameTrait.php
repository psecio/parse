<?php

namespace Psecio\Parse\Rule\Helper;

/**
 * Helper that defines getName()
 */
trait NameTrait
{
    /**
     * Get rule name
     *
     * @return string
     */
    public function getName()
    {
        return preg_replace('/^.+\\\\/', '', get_class($this));
    }
}
