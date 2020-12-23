<?php

namespace Jahuty;

class ClientTest extends \PHPUnit\Framework\TestCase
{
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

    /**
     * An end-to-end test using a live key and snippet.
     */
    public function testRequest(): void
    {
        $jahuty = new Client('kn2Kj5ijmT2pH6ZKqAQyNexUqKeRM4VG6DDgWN1lIcc');

        $render = $jahuty->snippets->render(1);

        $this->assertEquals(
            '<p>This is my first snippet!</p>',
            $render->getContent()
        );
    }
}
