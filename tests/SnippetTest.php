<?php

namespace Jahuty\Jahuty;

use BadMethodCallException;
use Jahuty\Jahuty\Jahuty;
use PHPUnit\Framework\TestCase;

class SnippetTest extends TestCase
{
    public function testGetThrowsExceptionIfKeyIsNull(): void
    {
        $this->expectException(BadMethodCallException::class);

        Snippet::get(1);
    }

    public function testGet(): void
    {
        Jahuty::setKey('kn2Kj5ijmT2pH6ZKqAQyNexUqKeRM4VG6DDgWN1lIcc');

        $snippet = Snippet::get(1);

        $this->assertEquals(1, $snippet->getId());
        $this->assertEquals('This is my first snippet!', $snippet->getContent());
    }
}
