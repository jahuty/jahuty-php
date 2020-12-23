<?php

namespace Jahuty\Resource;

use GuzzleHttp\Psr7\Response;
use Jahuty\Action\Show;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testNewThrowsExceptionWhenResponseIsUnexpected(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new Show('render', 1);

        // Use a response with an error status code but without the
        // application/problem+json content type.
        $response = new Response(500, [], "<p>Fatal error</p>");

        (new Factory())->new($action, $response);
    }

    public function testNewReturnsProblemWhenResponseIsProblem(): void
    {
        $action = new Show('render', 1);

        $response = new Response(
            500,
            ['Content-Type' => 'application/problem+json'],
            '{ "status": 500, "type": "foo", "detail": "bar" }'
        );

        $resource = (new Factory())->new($action, $response);

        $this->assertInstanceOf(Problem::class, $resource);
    }

    public function testNewThrowsExceptionWhenResourceDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        // An action on a non-existant resource.
        $action = new Show('foo', 1);

        (new Factory())->new($action, new Response(200));
    }

    public function testNewReturnsResource(): void
    {
        $action = new Show('render', 1);

        $response = new Response(
            200,
            [],
            '{ "content": "foo" }'
        );

        $resource = (new Factory())->new($action, $response);

        $this->assertInstanceOf(Render::class, $resource);
    }
}
