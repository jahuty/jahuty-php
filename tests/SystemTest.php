<?php

namespace Jahuty;

class SystemTest extends \PHPUnit\Framework\TestCase
{
    public function testRender(): void
    {
        $jahuty = new Client('kn2Kj5ijmT2pH6ZKqAQyNexUqKeRM4VG6DDgWN1lIcc');

        $render = $jahuty->snippets->render(1);

        $this->assertEquals(
            '<p>This is my first snippet!</p>',
            $render->getContent()
        );
    }

    public function testRenderCaching(): void
    {
        $jahuty = new Client('kn2Kj5ijmT2pH6ZKqAQyNexUqKeRM4VG6DDgWN1lIcc');

        // The first call requests the snippet and warms the cache.
        $render = $jahuty->snippets->render(1);

        // The second request hits the cache.
        $start  = microtime(true);
        $render = $jahuty->snippets->render(1);
        $end    = microtime(true);

        // The actual time should less than a maximum number of milliseconds.
        $maximum = 1;
        $actual  = ($end - $start) * 1000;

        $this->assertLessThan($maximum, $actual);
    }
}
