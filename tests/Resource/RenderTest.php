<?php

namespace Jahuty\Resource;

class RenderTest extends \PHPUnit\Framework\TestCase
{
    private $payload;

    public function setUp(): void
    {
        $this->payload = ['snippet_id' => 1, 'content' => 'foo'];
    }

    public function testFromThrowsExceptionIfContentsDoesNotExist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['content']);

        Render::from($this->payload);
    }

    public function testFromThrowsExceptionWhenSnippetIdIsMissing(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['snippet_id']);

        Render::from($this->payload);
    }

    public function testFromReturnsRenderWhenPayloadIsValid(): void
    {
        $expected = new Render(1, 'foo');
        $actual   = Render::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testFromReturnsRenderWhenExtraAttributesPresent(): void
    {
        $this->payload['foo'] = 'bar';

        $expected = new Render(1, 'foo');
        $actual   = Render::from($this->payload);

        $this->assertEquals($expected, $actual);
    }

    public function testGetContent(): void
    {
        $this->assertEquals('foo', (new Render(1, 'foo'))->getContent());
    }

    public function testGetSnippetId(): void
    {
        $this->assertEquals(1, (new Render(1, 'foo'))->getSnippetId());
    }

    public function testToString(): void
    {
        $this->assertEquals('foo', (string)new Render(1, 'foo'));
    }

    // Required for caching.
    public function testObjectSpportsLosslessSerializationAndDeserialization(): void
    {
        $render = new Render(1, 'foo');

        $this->assertEquals($render, unserialize(serialize($render)));
    }
}
