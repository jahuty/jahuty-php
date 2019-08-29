<?php

namespace Jahuty\Snippet\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Jahuty\Snippet\Data\Snippet;
use Jahuty\Snippet\Exception\NotOk;
use JsonException;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
    public function testInvokeThrowsExceptionIfResponseInvalid(): void
    {
        $this->expectException(JsonException::class);

        // mock a response to be returned with an invalid JSON body
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn('foo');

        $client = $this->createMock(Client::class);
        $client->method('send')->willReturn($response);

        $sut = new Get('foo', $client);

        $sut(1);
    }

    public function testInvokeThrowsExceptionIfResponseNotOk(): void
    {
        $this->expectException(NotOk::class);

        // mock a valid problem+json response
        $body = '{"status": 1, "type":"foo", "detail": "bar"}';

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($body);
        $response->method('getStatusCode')->willReturn(404);

        $client = $this->createMock(Client::class);
        $client->method('send')->willReturn($response);

        $sut = new Get('foo', $client);

        $sut(1);
    }

    public function testInvokeIfOk(): void
    {
        // mock a valid response
        $body = '{"id": 1, "content":"foo"}';

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($body);
        $response->method('getStatusCode')->willReturn(200);

        $client = $this->createMock(Client::class);
        $client->method('send')->willReturn($response);

        $sut = new Get('foo', $client);

        $expected = new Snippet(1, 'foo');
        $actual   = $sut(1);

        $this->assertEquals($expected, $actual);
    }
}
