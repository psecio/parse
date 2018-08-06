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
    public function getType(): string
    {
        return 'Node';
    }

    /**
     * Gets the names of the sub nodes.
     *
     * @return array Names of sub nodes
     */
    public function getSubNodeNames(): array
    {
        return [];
    }

    /**
     * Gets line the node started in.
     *
     * @return int Line
     */
    public function getLine(): int
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
     * Replaces all the attributes of this node.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this->attributes;
    }

    /**
     * Returns whether an attribute exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute($key): bool
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
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets line the node started in.
     *
     * Requires the 'startLine' attribute to be enabled in the lexer (enabled by default).
     *
     * @return int Start line (or -1 if not available)
     */
    public function getStartLine() : int
    {
        return 0;
    }


    /**
     * Gets the line the node ended in.
     *
     * Requires the 'endLine' attribute to be enabled in the lexer (enabled by default).
     *
     * @return int End line (or -1 if not available)
     */
    public function getEndLine() : int
    {
        return 0;
    }

    /**
     * Gets the token offset of the first token that is part of this node.
     *
     * The offset is an index into the array returned by Lexer::getTokens().
     *
     * Requires the 'startTokenPos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int Token start position (or -1 if not available)
     */
    public function getStartTokenPos() : int
    {
        return 0;
    }

    /**
     * Gets the token offset of the last token that is part of this node.
     *
     * The offset is an index into the array returned by Lexer::getTokens().
     *
     * Requires the 'endTokenPos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int Token end position (or -1 if not available)
     */
    public function getEndTokenPos() : int
    {
        return 0;
    }
    /**
     * Gets the file offset of the first character that is part of this node.
     *
     * Requires the 'startFilePos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int File start position (or -1 if not available)
     */
    public function getStartFilePos() : int
    {
        return 0;
    }

    /**
     * Gets the file offset of the last character that is part of this node.
     *
     * Requires the 'endFilePos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int File end position (or -1 if not available)
     */
    public function getEndFilePos() : int
    {
        return 0;
    }

    /**
     * Gets all comments directly preceding this node.
     *
     * The comments are also available through the "comments" attribute.
     *
     * @return Comment[]
     */
    public function getComments() : array
    {
        return [];
    }
}
