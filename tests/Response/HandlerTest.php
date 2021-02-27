<?php

namespace Jahuty\Response;

use GuzzleHttp\Psr7\Response;
use Jahuty\Action\{Index, Show};
use Jahuty\Resource\{Problem, Render};

class HandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testHandleThrowsExceptionWhenResponseIsUnexpected(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $action = new Show('render', 1);

        // Use a response with an error status code but without the
        // application/problem+json content type.
        $response = new Response(500, [], "<p>Fatal error</p>");

        (new Handler())->handle($action, $response);
    }

    public function testHandleReturnsProblemWhenResponseIsProblem(): void
    {
        $action = new Show('render', 1);

        $response = new Response(
            500,
            ['Content-Type' => 'application/problem+json'],
            '{ "status": 500, "type": "foo", "detail": "bar" }'
        );

        $resource = (new Handler())->handle($action, $response);

        $this->assertInstanceOf(Problem::class, $resource);
    }

    public function testHandleThrowsExceptionWhenResourceDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        // An action on a non-existant resource.
        $action = new Show('foo', 1);

        (new Handler())->handle($action, new Response(200, [], '{}'));
    }

    public function testHandleReturnsResource(): void
    {
        $action = new Show('render', 1);

        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{ "content": "foo" }'
        );

        $resource = (new Handler())->handle($action, $response);

        $this->assertInstanceOf(Render::class, $resource);
    }

    public function testHandleReturnsCollection(): void
    {
        $action = new Index('render');

        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            '[{ "id": 1, "content": "foo" }, { "id": 2, "content": "bar" }]'
        );

        $result = (new Handler())->handle($action, $response);

        $this->assertTrue(is_array($result));
    }
}
