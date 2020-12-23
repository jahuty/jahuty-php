<?php

namespace Jahuty\Request;

use Jahuty\Action\Show;
use Psr\Http\Message\RequestInterface;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testNew(): void
    {
        $action = new Show('render', 1, ['bar' => 'baz']);

        $this->assertInstanceOf(
            RequestInterface::class,
            (new Factory())->new($action)
        );
    }
}
