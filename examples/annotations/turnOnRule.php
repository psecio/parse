<?php

/**
 * This class turns off BooleanIdentity.
 *
 * @psecio\parse\disable BooleanIdentity
 */
class testTurnOnRule
{
    public function shouldError()
    {
        if ($ignoreMe == true) {
            echo "Check your bools!\n";
        }
    }

    /**
     * @psecio\parse\enable BooleanIdentity
     */
    public function shouldNotError()
    {
        if ($respectMyAuthority == true) {
            echo "I'm not responsible for your bools.!\n";
        }

        /** @psecio\parse\disable booleanidentity */
        if ($butThisCanBeIgnored == false) {
            echo "But..yeah. Huh.\n";
        }

        if ($dontIgnoreThis == true) {
            echo "Now we do the dance of joy!\n";
        }
    }
}