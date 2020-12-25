<?php

namespace Jahuty\Cache;

use Jahuty\Action\{Action, Show};
use Jahuty\Client;
use Jahuty\Resource\Resource;
use Psr\SimpleCache\CacheInterface;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructThrowsExceptionWhenTtlIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Manager(
            $this->createMock(Client::class),
            $this->createMock(CacheInterface::class),
            'foo'  // must be null, int, or DateInterval
        );
    }

    public function testFetchThrowsExceptionIfTtlIsInvalid(): void
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

        // stub cache to return the resource
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn($resource);

        // instantiate manager with the stubbed cache
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

    public function testFetchReturnsResourceWhenDefaultTtlDoesNotExist(): void
    {
        // stub a resource to return
        $resource = $this->createMock(Resource::class);

        // stub an action to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // define the expected cache key given the data above
        $key = "jahuty_foo_1";

        // stub the cache to miss and expect set
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->identicalTo($key),
                $this->identicalTo($resource),
                $this->identicalTo(null)
            );

        // stub client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs
        $manager = new Manager($client, $cache);

        $this->assertSame($resource, $manager->fetch($action));
    }

    public function testFetchReturnsResourceWhenDefaultTtlDoesExist(): void
    {
        $defaultTtl = 60;

        // stub a resource to return
        $resource = $this->createMock(Resource::class);

        // stub an action to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // define the expected cache key given the data above
        $key = "jahuty_foo_1";

        // stub the cache to miss and expect set
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->identicalTo($key),
                $this->identicalTo($resource),
                $this->identicalTo($defaultTtl)
            );

        // stub client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs
        $manager = new Manager($client, $cache, $defaultTtl);

        $this->assertSame($resource, $manager->fetch($action));
    }

    public function testFetchReturnsResourceWhenLocalTtlDoesExist(): void
    {
        $globalTtl = 10;
        $localTtl  = 100;

        // stub a resource to return
        $resource = $this->createMock(Resource::class);

        // stub an action to fetch
        $action = $this->createMock(Show::class);
        $action->method('getResource')->willReturn('foo');
        $action->method('getId')->willReturn(1);

        // define the expected cache key given the data above
        $key = "jahuty_foo_1";

        // stub the cache to miss and expect set
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->identicalTo($key),
                $this->identicalTo($resource),
                $this->identicalTo($localTtl)
            );

        // stub client to return the resource
        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($resource);

        // instantiate manager with the stubs
        $manager = new Manager($client, $cache, $globalTtl);

        $this->assertSame($resource, $manager->fetch($action, $localTtl));
    }
}
