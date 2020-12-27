<?php

namespace Jahuty;

use donatj\MockWebServer\{MockWebServer, Response};
use Jahuty\Action\Show;
use Jahuty\Cache\Ttl;
use Jahuty\Resource\Render;
use Psr\SimpleCache\CacheInterface;

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

    public function testConstructThrowsExceptionWhenCacheInvalid(): void
    {
        $this->expectException(\InvalidARgumentException::class);

        (new Client('foo', ['cache' => 'foo']));
    }

    public function testConstructThrowsExceptionWhenTtlInvalid(): void
    {
        $this->expectException(\InvalidARgumentException::class);

        // TTL must be null, int, or DateInterval.
        (new Client('foo', ['ttl' => 'foo']));
    }

    public function testMagicGetThrowsExceptionWhenServiceDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        (new Client('foo'))->bar;
    }

    public function testMagicGetReturnsServiceWhenServiceDoesExist(): void
    {
        $this->assertInstanceOf(
            Service\Snippet::class,
            (new Client('foo'))->snippets
        );
    }

    public function testFetchReturnsResourceWhenSuccess(): void
    {
        $id      = 1;
        $content = 'foo';

        $this->setupEndpointWithSuccess($id, $content);

        // Mock the cache to store the returned value.
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        $client = new Client('1234abcd', [
            'base_uri' => self::$server->getServerRoot(),
            'cache'    => $cache
        ]);

        $action = new Show('render', $id);

        $expected = new Render($content);
        $actual   = $client->fetch($action, $this->createMock(Ttl::class));

        $this->assertEquals($expected, $actual);
    }

    public function testFetchThrowsExceptionWhenProblem(): void
    {
        $id = 1;

        $this->expectException(Exception\Error::class);

        $this->setupEndpointWithProblem($id);

        // Mock the cache NOT TO store the returned problem.
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->never())->method('set');

        $client = new Client('1234abcd', [
            'base_uri' => self::$server->getServerRoot(),
            'cache'    => $cache
        ]);

        $client->fetch(
            new Show('render', $id),
            $this->createMock(Ttl::class)
        );
    }

    public function testRequestThrowsExceptionWhenProblem(): void
    {
        $id = 1;

        $this->expectException(Exception\Error::class);

        $this->setupEndpointWithProblem($id);

        $client = new Client('1234abcd', [
            'base_uri' => self::$server->getServerRoot()
        ]);

        $client->request(new Show('render', $id));
    }

    public function testRequestReturnsResourceWhenSuccess(): void
    {
        $id      = 1;
        $content = 'foo';

        $this->setupEndpointWithSuccess($id, $content);

        $client = new Client('1234abcd', [
            'base_uri' => self::$server->getServerRoot()
        ]);

        $action = new Show('render', $id);

        $expected = new Render($content);
        $actual   = $client->request($action);

        $this->assertEquals($expected, $actual);
    }

    private function setupEndpointWithSuccess($id = 1, $content = 'foo'): void
    {
        $response = new Response('{"content":"'. $content .'"}', [], 200);

        self::$server->setResponseOfPath("snippets/$id/render", $response);
    }

    private function setupEndpointWithProblem($id = 1): void
    {
        $response = new Response(
            '{"status":404,"type":"foo","detail":"bar"}',
            ['Content-Type' => 'application/problem+json'],
            404
        );

        self::$server->setResponseOfPath("snippets/$id/render", $response);
    }
}
