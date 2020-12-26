<?php

namespace Jahuty;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructThrowsExceptionWhenCacheInvalid(): void
    {
        $this->expectException(\InvalidARgumentException::class);

        (new Client('foo', ['cache' => 'foo']));
    }

    public function testConstructThrowsExceptionWhenTtlInvalid(): void
    {
        $this->expectException(\InvalidARgumentException::class);

        (new Client('foo', ['ttl' => 'foo']));
    }

    public function testMagicGetThrowsExceptionWhenServiceDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        (new Client('foo'))->bar;
    }

    public function testMagicGetReturnsServiceWhenServiceDoesExist(): void
    {
        $this->assertInstanceOf(
            Service\Snippet::class,
            (new Client('foo'))->snippets
        );
    }
}
