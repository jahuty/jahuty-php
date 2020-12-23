<?php

namespace Jahuty\Uri;

use Jahuty\Action\{Action, Show};
use Psr\Http\Message\UriInterface;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testRouteThrowsExceptionIfActionNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new class('foo', []) extends Action {
            // nothing
        };

        (new Factory())->new($action);
    }

    public function testRouteThrowsExceptionIfResourceNotFound(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new Show('foo', 1);

        (new Factory())->new($action);
    }

    public function testRouteReturnsUri(): void
    {
        $action = new Show('render', 1);

        $this->assertInstanceOf(
            UriInterface::class,
            (new Factory())->new($action)
        );
    }

    public function testRouteReturnsUriWhenParamsDoNotExist(): void
    {
        $action = new Show('render', 1);

        $this->assertEquals(
            'https://www.example.com/snippets/1/render',
            (string)(new Factory('https://www.example.com'))->new($action)
        );
    }

    public function testRouteReturnsUriWhenParamsDoExist(): void
    {
        $action = new Show('render', 1, ['foo' => '{"foo":"bar"}']);

        $this->assertEquals(
            'https://www.example.com/snippets/1/render?foo=%7B%22foo%22%3A%22bar%22%7D',
            (string)(new Factory('https://www.example.com'))->new($action)
        );
    }
}
