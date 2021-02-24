<?php

namespace Jahuty\Resource;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateThrowsExceptionWhenResourceDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        (new Factory())->create('foo', []);
    }

    public function testCreateReturnsResource(): void
    {
        $payload = ['content' => '<p>foo</p>'];

        $resource = (new Factory())->create('render', $payload);

        $this->assertInstanceOf(Render::class, $resource);
    }
}
