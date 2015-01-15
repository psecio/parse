<?php

/**
 * Turn off the rule for the following function
 *
 * @psecio\parse\ignore EvalFunction
 */
function needsEval()
{
    eval('echo "I need eval()\n"');
}

/**
 * Previous rule should not affect this one.
 */
function evalIsBad()
{
    eval('echo "Eval is Evil!"');
}
