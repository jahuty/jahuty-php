<?php

namespace Jahuty\Resource;

class ProblemTest extends \PHPUnit\Framework\TestCase
{
    private $payload;

    public function setUp(): void
    {
        $this->payload = ['status' => 1, 'type' => 'foo', 'detail' => 'bar'];
    }

    public function testFromThrowsExceptionIfDetailDoesNotExist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['detail']);

        Problem::from($this->payload);
    }

    public function testFromThrowsExceptionIfStatusDoesNotExist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['status']);

        Problem::from($this->payload);
    }

    public function testFromThrowsExceptionIfTypeDoesNotExist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        unset($this->payload['type']);

        Problem::from($this->payload);
    }

    public function testFrom(): void
    {
        $this->assertInstanceOf(Problem::class, Problem::from($this->payload));
    }

    public function testGetDetail(): void
    {
        $this->assertEquals('bar', (new Problem(1, 'foo', 'bar'))->getDetail());
    }

    public function testGetStatus(): void
    {
        $this->assertEquals(1, (new Problem(1, 'foo', 'bar'))->getStatus());
    }

    public function testGetType(): void
    {
        $this->assertEquals('foo', (new Problem(1, 'foo', 'bar'))->getType());
    }
}
