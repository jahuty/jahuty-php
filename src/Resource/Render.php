<?php

namespace Jahuty\Resource;

class Render implements Resource
{
    private $content;

    private $snippetId;

    public function __construct(int $snippetId, string $content)
    {
        $this->snippetId = $snippetId;
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public static function from(array $payload): Render
    {
        if (!\array_key_exists('snippet_id', $payload)) {
            throw new \BadMethodCallException("Key 'snippet_id' does not exist");
        }

        if (!\array_key_exists('content', $payload)) {
            throw new \BadMethodCallException("Key 'content' does not exist");
        }

        return new Render($payload['snippet_id'], $payload['content']);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSnippetId(): int
    {
        return $this->snippetId;
    }
}
