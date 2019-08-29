<?php

namespace Jahuty\Snippet;

use BadMethodCallException;
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
        Snippet::key('78e202009659616eceed79c01a75bfe9');

        $snippet = Snippet::get(1);

        $this->assertEquals(1, $snippet->getId());
        $this->assertEquals('This is my first snippet!', $snippet->getContent());
    }
}
