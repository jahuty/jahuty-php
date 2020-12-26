<?php

namespace Jahuty\Cache;

use Jahuty\Action\{Action, Show};
use Jahuty\Client;
use Jahuty\Resource\Resource;
use Psr\SimpleCache\CacheInterface as Cache;

class Manager
{
    private $cache;

    private $client;

    private $ttl;

    public function __construct(Client $client, Cache $cache, $ttl = null)
    {
        if ($ttl !== null &&
            $ttl !== (int)$ttl &&
            !($ttl instanceof \DateInterval)
        ) {
            throw new \InvalidArgumentException(
                "Parameter three, ttl, must be null, integer, or DateInterval"
            );
        }

        $this->client = $client;
        $this->cache  = $cache;
        $this->ttl    = $ttl;
    }

    public function fetch(Action $action, $ttl = null): Resource
    {
        if ($ttl !== null &&
            $ttl !== (int)$ttl &&
            !($ttl instanceof \DateInterval)
        ) {
            throw new \InvalidArgumentException(
                "Parameter two, ttl, must be null, integer, or DateInterval"
            );
        }

        $key = $this->getKey($action);

        if (null === ($resource = $this->cache->get($key))) {
            $resource = $this->client->request($action);
            $this->cache->set($key, $resource, $ttl ?: $this->ttl);
        }

        return $resource;
    }

    private function getKey(Show $action): string
    {
        return "jahuty_{$action->getResource()}_{$action->getId()}";
    }
}
