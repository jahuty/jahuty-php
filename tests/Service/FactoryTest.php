<?php

namespace Jahuty\Service;

use Jahuty\Client;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
    }

    public function testMagicGetThrowsExceptionWhenNameIsNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $factory = new Factory($this->client);

        $factory->foo;
    }

    public function testMagicGetReturnsClassWhenNameIsFound(): void
    {
        $factory = new Factory($this->client);

        $this->assertInstanceOf(Snippet::class, $factory->snippets);
    }

    public function testMagicGetReturnsMemoizedClassWhenRequestedAgain(): void
    {
        $factory = new Factory($this->client);

        $service = $factory->snippets;

        $this->assertSame($service, $factory->snippets);
    }
}
