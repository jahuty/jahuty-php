<?php
/**
 * @copyright  2019 Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty\Data;

use BadMethodCallException;

class Render
{
    private $content;

    private $id;

    public function __construct(int $id, string $content)
    {
        $this->id      = $id;
        $this->content = $content;
    }

    public static function from(array $payload): Render
    {
        if (!array_key_exists('id', $payload)) {
            throw new BadMethodCallException("Key 'id' does not exist");
        }

        if (!array_key_exists('content', $payload)) {
            throw new BadMethodCallException("Key 'content' does not exist");
        }

        return new Render($payload['id'], $payload['content']);
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
