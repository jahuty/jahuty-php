<?php

namespace Jahuty\Api;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testGetBody(): void
    {
        $this->assertEquals(
            ['foo' => 'bar'],
            (new Response(1, ['foo' => 'bar']))->getBody()
        );
    }

    public function testGetHeaderThrowsExceptionIfDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        (new Response(1, []))->getHeader('foo');
    }

    public function testGetHeaderReturnsHeaderIfDoesExist(): void
    {
        $this->assertEquals(
            ['bar'],
            (new Response(1, [], ['foo' => ['bar']]))->getHeader('foo')
        );
    }

    public function testGetHeaderIsCaseInsensitive(): void
    {
        $this->assertEquals(
            ['bar'],
            (new Response(1, [], ['fOO' => ['bar']]))->getHeader('foo')
        );
    }

    public function testGetHeaders(): void
    {
        $this->assertEquals(
            ['foo' => ['bar']],
            (new Response(1, [], ['foo' => ['bar']]))->getHeaders()
        );
    }

    public function testGetStatus(): void
    {
        $this->assertEquals(1, (new Response(1, []))->getStatus());
    }

    public function testHasHeaderReturnsFalseIfDoesNotExist(): void
    {
        $this->assertFalse((new Response(1, []))->hasHeader('foo'));
    }

    public function testHasHeaderReturnsTrueIfDoesExist(): void
    {
        $this->assertTrue(
            (new Response(1, [], ['foo' => ['bar']]))->hasHeader('foo')
        );
    }

    public function testHasHeaderIsCaseInsensitive(): void
    {
        $this->assertTrue(
            (new Response(1, [], ['FoO' => ['bar']]))->hasHeader('foo')
        );
    }

    public function testIsErrorReturnsTrueIfError(): void
    {
        $this->assertTrue((new Response(500, []))->isError());
    }

    public function testIsErrorReturnsFalseIfNotError(): void
    {
        $this->assertFalse((new Response(200, []))->isError());
    }

    public function testIsProblemReturnsTrueIfProblem(): void
    {
        $response = new Response(
            500,
            ['foo' => 'bar'],
            ['Content-Type' => ['application/problem+json']]
        );

        $this->assertTrue($response->isProblem());
    }

    public function testIsProblemReturnsFalseIfNotProblem(): void
    {
        $this->assertFalse((new Response(200, []))->isProblem());
    }

    public function testIsSuccessReturnsTrueIfSuccess(): void
    {
        $this->assertTrue((new Response(200, []))->isSuccess());
    }

    public function testIsSuccessReturnsFalseIfNotSuccess(): void
    {
        $this->assertFalse((new Response(500, []))->isSuccess());
    }
}
