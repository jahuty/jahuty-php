<?php

namespace Jahuty;

class SystemTest extends \PHPUnit\Framework\TestCase
{
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
