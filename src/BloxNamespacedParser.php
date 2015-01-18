<?php

namespace Psecio\Parse;

use Eloquent\Blox\BloxParser;
use Eloquent\Blox\Element\DocumentationTag;

class BloxNamespacedParser extends BloxParser
{
    /**
     * @param string $blockComment
     *
     * @return array
     */
    protected function parseBlockCommentLines($blockComment)
    {
        $lines = array();
        if (preg_match_all('~^\s*(?:/\*)?\* ?(?!/)(.*?)(?:\s*\*/)?$~m', $blockComment, $matches)) {
            $lines = $matches[1];
        }

        return $lines;
    }

    /**
     * @param array &$blockCommentLines
     *
     * @return DocumentationTags
     */
    protected function parseBlockCommentTags(array &$blockCommentLines)
    {
        $tags = array();
        $currentTagName = $currentTagContent = null;
        foreach ($blockCommentLines as $index => $blockCommentLine) {
            $isTagLine = preg_match(
                '~^@([\w\\\\]+)(?:\s+(.*))?\s*$~',
                $blockCommentLine,
                $matches
            );
            $isEmptyLine = '' === trim($blockCommentLine);

            if (
                ($isTagLine || $isEmptyLine) &&
                null !== $currentTagName
            ) {
                if ('' === $currentTagContent) {
                    $currentTagContent = null;
                }
                $tags[] = new DocumentationTag(
                    $currentTagName,
                    $currentTagContent
                );

                $currentTagName = $currentTagContent = null;
            }

            if ($isTagLine) {
                $currentTagName = $matches[1];
                $currentTagContent = '';
                if (array_key_exists(2, $matches)) {
                    $currentTagContent = $matches[2];
                }
            } elseif (!$isEmptyLine) {
                $currentTagContent .= ' ' . ltrim($blockCommentLine);
            }

            if (null !== $currentTagName || count($tags) > 0) {
                unset($blockCommentLines[$index]);
            }
        }
        if (null !== $currentTagName) {
            if ('' === $currentTagContent) {
                $currentTagContent = null;
            }
            $tags[] = new DocumentationTag(
                $currentTagName,
                $currentTagContent
            );
        }

        return $tags;
    }

}