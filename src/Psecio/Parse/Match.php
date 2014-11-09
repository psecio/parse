<?php

namespace Psecio\Parse;

abstract class Match
{
	abstract public function execute($node, $config);
}