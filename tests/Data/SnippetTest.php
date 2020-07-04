<?php

namespace Jahuty\Jahuty\Data;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class RenderTest extends TestCase
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

        Render::from($this->payload);
    }

    public function testFromThrowsExceptionIfContentsDoesNotExist(): void
    {
        $this->expectException(BadMethodCallException::class);

        unset($this->payload['content']);

        Render::from($this->payload);
    }

    public function testFrom(): void
    {
        $expected = new Render(1, 'foo');
        $actual   = Render::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testGetContent(): void
    {
        $this->assertEquals('foo', (new Render(1, 'foo'))->getContent());
    }

    public function testGetId(): void
    {
        $this->assertEquals(1, (new Render(1, 'foo'))->getId());
    }

    public function testToString(): void
    {
        $this->assertEquals('foo', (string)new Render(1, 'foo'));
    }
}
