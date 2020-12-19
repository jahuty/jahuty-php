<?php

namespace Jahuty\Action;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    public function testRouteThrowsExceptionIfActionNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new class('foo', []) extends Action {
            // nothing
        };

        (new Router())->route($action);
    }

    public function testRouteThrowsExceptionIfResourceNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new Show('foo', 1);

        (new Router())->route($action);
    }

    public function testRouteReturnsString(): void
    {
        $action = new Show('render', 1);

        $this->assertTrue(is_string((new Router())->route($action)));
    }
}
