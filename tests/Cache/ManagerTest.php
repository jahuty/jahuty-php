<?php

namespace Jahuty\Cache;

use Jahuty\Action\{Action, Show};
use Jahuty\Client;
use Jahuty\Resource\Resource;
use Psr\SimpleCache\CacheInterface;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructThrowsExceptionWhenDefaultTtlIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Manager(
            $this->createMock(Client::class),
            $this->createMock(CacheInterface::class),
            'foo'  // must be null, int, or DateInterval
        );
    }

    public function testFetchThrowsExceptionWhenArgumentTtlIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $manager = new Manager(
            $this->createMock(Client::class),
            $this->createMock(CacheInterface::class),
            60
        );

        $action = $this->createMock(Action::class);

        $manager->fetch($action, 'foo');
    }

    public function testFetchReturnsResourceWhenResourceIsCached(): void
    {
        // stub a resource to return
        $resource = $this->createMock(Resource::class);

        // stub the cache to return the resource
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn($resource);

        // instantiate the manager with the stubbed cache
        $manager = new Manager($this->createMock(Client::class), $cache);

        $this->assertSame(
            $resource,
            $manager->fetch($this->createMock(Show::class))
        );
    }

    public function testFetchReturnsResourceWhenResourceIsNotCached(): void
    {
        // stub a resource to return
        $resource = $this->createMock(Resource::class);

        // stub cache to miss
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        // stub client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs
        $manager = new Manager($client, $cache);

        $this->assertSame(
            $resource,
            $manager->fetch($this->createMock(Show::class))
        );
    }

    public function testFetchReturnsResourceWhenTtlDoesNotExist(): void
    {
        // stub a resource for the cache to return
        $resource = $this->createMock(Resource::class);

        // stub an action for the manager to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // stub the cache to miss and mock the cache to set the resource
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/[a-z0-9]/'),
                $this->equalTo($resource),
                $this->equalTo(null)
            );

        // stub the jahuty client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate the manager with the stubs
        $manager = new Manager($client, $cache);

        $this->assertSame($resource, $manager->fetch($action));
    }

    public function testFetchReturnsResourceWhenGlobalTtlDoesExist(): void
    {
        $defaultTtl = 60;

        // stub a resource for the cache to return
        $resource = $this->createMock(Resource::class);

        // stub an action for the manager to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // stub the cache to miss and expect set
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/[a-z0-9]/'),
                $this->equalTo($resource),
                $this->equalTo($defaultTtl)
            );

        // stub the jahuty client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs and default ttl
        $manager = new Manager($client, $cache, $defaultTtl);

        $this->assertSame($resource, $manager->fetch($action));
    }

    public function testFetchReturnsResourceWhenLocalTtlDoesExist(): void
    {
        $globalTtl = 10;
        $localTtl  = 100;

        // stub a resource for the cache to return
        $resource = $this->createMock(Resource::class);

        // stub an action for the manager to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // stub the cache to miss and expect a set
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->matchesRegularExpression('/[a-z0-9]/'),
                $this->equalTo($resource),
                $this->equalTo($localTtl)
            );

        // stub client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs
        $manager = new Manager($client, $cache, $globalTtl);

        $this->assertSame($resource, $manager->fetch($action, $localTtl));
    }
}
