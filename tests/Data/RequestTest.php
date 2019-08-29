<?php

namespace Jahuty\Snippet\Data;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testConstruct(): void
    {
        $request = new Request('foo', 1);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals(
            'https://www.jahuty.com/api/snippets/1',
            (string)$request->getUri()
        );
        $this->assertEquals(
            'Bearer foo',
            $request->getHeader('Authorization')[0]
        );
    }
}
