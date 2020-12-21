<?php

namespace Jahuty\Request;

use Jahuty\Action\Show;
use Psr\Http\Message\RequestInterface;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateRequest(): void
    {
        $action = new Show('render', 1, ['bar' => 'baz']);

        $this->assertInstanceOf(
            RequestInterface::class,
            (new Factory())->createRequest($action)
        );
    }
}
