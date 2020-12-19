<?php

namespace Jahuty\Resource;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testNewThrowsExceptionWhenNameDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        (new Factory())->new('foo', []);
    }

    public function testNewReturnsResourceWhenNameDoesExist(): void
    {
        $this->assertInstanceOf(
            Render::class,
            (new Factory())->new('render', ['content' => 'foo'])
        );
    }
}
