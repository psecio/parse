<?php

namespace Psecio\Parse\Fakes;

use PhpParser\Node;

class FakeNode implements Node
{
    protected $attributes = [];
    protected $docComment = null;

    /**
     * For the fake. Create a new one.
     *
     * @param string $docComment the comment for this node
     */
    public function __construct($docComment = '')
    {
        $this->setDocComment($docComment);
    }

    /**
     * For the fake, set the doc comment of this node
     *
     * @param string $docComment  The comment to use
     */
    public function setDocComment($docComment)
    {
        $this->docComment = $docComment;
    }

    /**
     * Gets the type of the node.
     *
     * @return string Type of the node
     */
    public function getType()
    {
        return 'Node';
    }

    /**
     * Gets the names of the sub nodes.
     *
     * @return array Names of sub nodes
     */
    public function getSubNodeNames()
    {
        return [];
    }

    /**
     * Gets line the node started in.
     *
     * @return int Line
     */
    public function getLine()
    {
        return 0;
    }

    /**
     * Sets line the node started in.
     *
     * @param int $line Line
     */
    public function setLine($line)
    {
    }

    /**
     * Gets the doc comment of the node.
     *
     * The doc comment has to be the last comment associated with the node.
     *
     * @return null|string|Comment\Doc Doc comment object or null
     */
    public function getDocComment()
    {
        return $this->docComment;
    }

    /**
     * Sets an attribute on a node.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Returns whether an attribute exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Returns the value of an attribute.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function &getAttribute($key, $default = null)
    {
        $r =& $default;
        if (isset($this->attributes[$key])) {
            $r =& $this->attributes[$key];
        }

        return $r;
    }

    /**
     * Returns all attributes for the given node.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
