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

    public function __construct(Client $client, Cache $cache, Ttl $ttl)
    {
        $this->client = $client;
        $this->cache  = $cache;
        $this->ttl    = $ttl;
    }

    public function fetch(Action $action, Ttl $ttl): Resource
    {
        if ($ttl->isNull()) {
            $ttl = $this->ttl;
        }

        $key = $this->getKey($action);

        if (null === ($resource = $this->cache->get($key))) {
            $resource = $this->client->request($action);
            $this->cache->set($key, $resource, $ttl->toSeconds());
        }

        return $resource;
    }

    /**
     * Returns a cache key for a resource, id, and params combination.
     *
     * I use an md5 hash to allow inputs of any length and outputs of a known
     * (and valid) length. According to PSR-16, valid keys are, "the characters
     * A-Z, a-z, 0-9, _, and . in any order in UTF-8 encoding and a length of up
     * to 64 characters."
     */
    private function getKey(Show $action): string
    {
        $segments = [
             'jahuty',
             $action->getResource(),
             $action->getId(),
             json_encode($action->getParams(), JSON_THROW_ON_ERROR)
        ];

        $key = implode('\\', $segments);

        return md5($key);
    }
}
