<?php

namespace Jahuty\Jahuty\Data;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class RenderTest extends TestCase
{
    private $payload;

    public function setUp(): void
    {
        $this->payload = ['content' => 'foo'];
    }

    public function testFromThrowsExceptionIfContentsDoesNotExist(): void
    {
        $this->expectException(BadMethodCallException::class);

        unset($this->payload['content']);

        Render::from($this->payload);
    }

    public function testFrom(): void
    {
        $expected = new Render('foo');
        $actual   = Render::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testGetContent(): void
    {
        $this->assertEquals('foo', (new Render('foo'))->getContent());
    }

    public function testToString(): void
    {
        $this->assertEquals('foo', (string)new Render('foo'));
    }
}
