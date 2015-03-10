<?php

/**
 * This class turns off BooleanIdentity.
 *
 * @psecio\parse\disable BooleanIdentity // This class has lots of brokeness here to be fixed later
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
     * @psecio\parse\enable BooleanIdentity  Got the problems solved in this method
     */
    public function shouldNotError()
    {
        if ($respectMyAuthority == true) {
            echo "I'm not responsible for your bools.!\n";
        }

        /** @psecio\parse\disable booleanidentity  But there is a reason for this */
        if ($butThisCanBeIgnored == false) {
            echo "But..yeah. Huh.\n";
        }

        if ($dontIgnoreThis == true) {
            echo "Now we do the dance of joy!\n";
        }
    }
}
