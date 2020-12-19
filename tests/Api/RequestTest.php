<?php

namespace Jahuty\Api;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetMethod(): void
    {
        $this->assertEquals('foo', (new Request('foo', 'bar'))->getMethod());
    }

    public function testGetPath(): void
    {
        $this->assertEquals('bar', (new Request('foo', 'bar'))->getPath());
    }

    public function testGetOptions(): void
    {
        $this->assertEquals([], (new Request('foo', 'bar'))->getOptions());
    }

    public function testSetOption(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertSame($request, $request->setOption('foo', 'baz'));
    }

    public function testSetOptions(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertSame($request, $request->setOptions([]));
    }
}
