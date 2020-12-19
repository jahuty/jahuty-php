<?php

namespace Jahuty\Api;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response as MockResponse;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    private static $server;

    public static function setUpBeforeClass(): void
    {
        self::$server = new MockWebServer();
        self::$server->start();
    }

    public static function tearDownAfterClass(): void
    {
        self::$server->stop();
    }

    public function testSend(): void
    {
        $request = new Request('get', 'foo');

        // Mock an empty 200 response for the "/foo" path.
        $url = self::$server->setResponseOfPath(
            'foo',
            new MockResponse('{}', [], 200)
        );

        $client = new Client([
            'api_key'  => 'foo',
            'base_uri' => self::$server->getServerRoot()
        ]);

        $response = $client->send($request);

        // We can't test an expected Response here, because the mock server will
        // add default headers to the response like 'host', 'connection', etc.
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals([], $response->getBody());
    }

    public function testConstructThrowsExceptionWhenApiKeyDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Client([]);
    }

    public function testConstructThrowsExceptionWhenBaseUriDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Client(['base_uri' => null]);
    }
}
