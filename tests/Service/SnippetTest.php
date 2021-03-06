<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
use Jahuty\Cache\Ttl;
use Jahuty\Client;
use Jahuty\Resource\Render;
use Psr\SimpleCache\CacheInterface;

class SnippetTest extends \PHPUnit\Framework\TestCase
{
    public function testAllRenders(): void
    {
        // a collection of renders
        $render     = new Render(1, 'foo');
        $collection = [$render];

        // a successful api request
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($collection);

        // mock a cache miss and expect a write
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);
        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/jahuty_[a-z0-9]+/'),
                $this->equalTo($render),
                $this->equalTo(null)
            );

        $service = new Snippet($client, $cache, new Ttl());

        $service->allRenders('foo');
    }

    public function testRendersWhenParamsDoExist(): void
    {
        // the expected action, note the json-encoded params)
        $action = new Index('render', [
            'tag'    => 'foo',
            'params' => '{"*":{"foo":"bar"},"1":{"foo":"baz"}}'
        ]);

        // a no-op cache
        $cache = $this->createMock(CacheInterface::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue([]));

        $service = new Snippet($client, $cache, new Ttl());

        $service->allRenders('foo', [
            'params' => [
                '*' => [
                    'foo' => 'bar'
                ],
                1 => [
                    'foo' => 'baz'
                ]
            ]
        ]);
    }

    public function testRendersWithLatest(): void
    {
        // the expected action
        $action = new Index('render', ['tag' => 'foo', 'latest' => 1]);

        // a no-op cache
        $cache = $this->createMock(CacheInterface::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue([]));

        $service = new Snippet($client, $cache, new Ttl());

        $service->allRenders('foo', [
            'prefer_latest_content' => true
        ]);
    }

    public function testRenderReturnsRenderWhenCacheMiss(): void
    {
        // the action to request
        $action = new Show('render', 1);

        // the render to return
        $render = new Render(1, 'foo');

        // a cache miss
        $cache = $this->createMock(CacheInterface::class);

        // a successful api request
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($render);

        // a cache write
        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/jahuty_[a-z0-9]+/'),
                $this->equalTo($render),
                $this->equalTo(null)
            );

        // the service-under-test
        $service = new Snippet($client, $cache, new Ttl());

        $this->assertSame($render, $service->render(1));
    }

    public function testRenderReturnsRenderWhenCacheHit(): void
    {
        // the cached render
        $render = new Render(1, 'foo');

        // a cache hit
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn($render);

        // a no-op client
        $client = $this->createMock(Client::class);

        // the service-under-test
        $service = new Snippet($client, $cache, new Ttl());

        $this->assertSame($render, $service->render(1));
    }

    public function testRenderWhenParamsExist(): void
    {
        // the expected action, note the json-formatted params
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        // the render to return
        $render = new Render(1, 'foo');

        // a cache miss
        $cache = $this->createMock(CacheInterface::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue($render));

        $service = new Snippet($client, $cache, new Ttl());

        $service->render(1, ['params' => ['foo' => 'bar']]);
    }

    public function testRenderWhenTllExists(): void
    {
        $ttl = 60;

        // the render to return
        $render = new Render(1, 'foo');

        // a successful api request
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($render);

        // mock a cache miss and expect write to use the ttl argument
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);
        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/jahuty_[a-z0-9]+/'),
                $this->equalTo($render),
                $this->equalTo($ttl)
            );

        $service = new Snippet($client, $cache, new Ttl(120));

        $service->render(1, ['ttl' => $ttl]);
    }

    public function testRenderWhenLatestExists(): void
    {
        // the expected action
        $action = new Show('render', 1, ['latest' => 1]);

        // the render to return
        $render = new Render(1, 'foo');

        // a cache miss
        $cache = $this->createMock(CacheInterface::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue($render));

        $service = new Snippet($client, $cache, new Ttl());

        $service->render(1, ['prefer_latest_content' => true]);
    }
}
