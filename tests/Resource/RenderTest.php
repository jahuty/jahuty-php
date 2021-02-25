<?php

namespace Jahuty\Resource;

class RenderTest extends \PHPUnit\Framework\TestCase
{
    private $payload;

    public function setUp(): void
    {
        $this->payload = ['content' => 'foo'];
    }

    public function testFromThrowsExceptionIfContentsDoesNotExist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['content']);

        Render::from($this->payload);
    }

    public function testFrom(): void
    {
        $expected = new Render('foo');
        $actual   = Render::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testFromAcceptsUnusedAttributes(): void
    {
        $payload = ['content' => 'foo', 'bar' => 'baz'];

        $expected = new Render('foo');
        $actual   = Render::from($payload);

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

    public function testGetSnippetIdWhenSnippetIdExists(): void
    {
        $this->assertEquals(1, (new Render('foo', 1))->getSnippetId());
    }

    public function testGetSnippetIdWhenSnippetIdDoesNotExist(): void
    {
        $this->assertNull((new Render('foo'))->getSnippetId());
    }

    public function testHasSnippetIdWhenSnippetIdDoesNotExist(): void
    {
        $this->assertFalse((new Render('foo'))->hasSnippetId());
    }

    public function testHasSnippetIdWhenSnippetIdDoesExist(): void
    {
        $this->assertTrue((new Render('foo', 1))->hasSnippetId());
    }

    // Required for caching.
    public function testObjectSpportsLosslessSerializationAndDeserialization(): void
    {
        $render = new Render('foo');

        $this->assertEquals($render, unserialize(serialize($render)));
    }
}
