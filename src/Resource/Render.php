<?php

namespace Jahuty\Resource;

class Render implements Resource
{
    private $content;

    private $snippetId;

    public function __construct(string $content, int $snippetId = null)
    {
        $this->content = $content;
        $this->snippetId = $snippetId;
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public static function from(array $payload): Render
    {
        if (!\array_key_exists('content', $payload)) {
            throw new \BadMethodCallException("Key 'content' does not exist");
        }

        return new Render($payload['content'], $payload['snippet_id'] ?? null);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSnippetId(): ?int
    {
        return $this->snippetId;
    }

    public function hasSnippetId(): bool
    {
        return $this->snippetId !== null;
    }
}
