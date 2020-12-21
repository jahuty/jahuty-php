<?php

namespace Jahuty\Api;

use GuzzleHttp\Psr7\Request;
use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

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
        // Instantiate a PSR-7 response to the /foo endpoint.
        $request = new Request('get', self::$server->getServerRoot() . '/foo');

        // Stub an empty 200 response from the /foo endpoint.
        $url = self::$server->setResponseOfPath('foo', new Response('{}', [], 200));

        // Instantiate the API client (the API key doesn't matter).
        $sut = new Client('foo');

        $response = $client->send($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
