<?php

namespace Psecio\Parse\Fakes;

use Psecio\Parse\RuleInterface;
use PhpParser\Node;

class FakeRule implements RuleInterface
{
    private $name = 'SomeRule';
    private $validNodes = [];

    public function __construct($name = 'SomeRule', array $validNodes = [])
    {
        $this->name = $name;
        $this->validNodes = $validNodes;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return '';
    }

    public function getLongDescription()
    {
        return '';
    }

    public function isValid(Node $node)
    {
        $valid = in_array($node, $this->validNodes, true);
    }

    public function addValidNode($node)
    {
        $this->validNodes[] = $node;
    }
}
