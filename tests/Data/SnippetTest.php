<?php

namespace Jahuty\Jahuty\Data;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class SnippetTest extends TestCase
{
    private $payload;

    public function setUp(): void
    {
        $this->payload = ['id' => 1, 'content' => 'foo'];
    }

    public function testFromThrowsExceptionIfIdDoesNotExist(): void
    {
        $this->expectException(BadMethodCallException::class);

        unset($this->payload['id']);

        Snippet::from($this->payload);
    }

    public function testFromThrowsExceptionIfContentsDoesNotExist(): void
    {
        $this->expectException(BadMethodCallException::class);

        unset($this->payload['content']);

        Snippet::from($this->payload);
    }

    public function testFrom(): void
    {
        $expected = new Snippet(1, 'foo');
        $actual   = Snippet::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testGetContent(): void
    {
        $this->assertEquals('foo', (new Snippet(1, 'foo'))->getContent());
    }

    public function testGetId(): void
    {
        $this->assertEquals(1, (new Snippet(1, 'foo'))->getId());
    }

    public function testToString(): void
    {
        $this->assertEquals('foo', (string)new Snippet(1, 'foo'));
    }
}
