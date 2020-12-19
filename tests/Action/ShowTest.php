<?php

namespace Jahuty\Action;

class ShowTest extends \PHPUnit\Framework\TestCase
{
    public function testGetId(): void
    {
        $this->assertEquals(1, (new Show('foo', 1))->getId());
    }

    public function testGetParams(): void
    {
        $this->assertEquals(
            ['baz'=> 'qux'],
            (new Show('foo', 1, ['baz'=> 'qux']))->getParams()
        );
    }

    public function testGetResource(): void
    {
        $this->assertEquals('foo', (new Show('foo', 1))->getResource());
    }

    public function testHasParams(): void
    {
        $this->assertFalse((new Show('foo', 1))->hasParams());
    }
}
