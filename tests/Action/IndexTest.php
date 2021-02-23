<?php

namespace Jahuty\Action;

class IndexTest extends \PHPUnit\Framework\TestCase
{
    public function testGetParams(): void
    {
        $params = ['baz'=> 'qux'];

        $this->assertEquals($params, (new Index('foo', $params))->getParams());
    }

    public function testGetResource(): void
    {
        $this->assertEquals('foo', (new Index('foo'))->getResource());
    }

    public function testHasParams(): void
    {
        $this->assertFalse((new Index('foo'))->hasParams());
    }
}
