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

    /**
     * Reads and writes an action to the cache.
     *
     * In the case of a cache hit, the action's cached value is returned without
     * executing the action. In the case of a cache miss, the action will be
     * executed and the value cached and returned.
     *
     * @param   Action  $action  the action to fetch
     * @param   Ttl     $ttl     the action's time-to-live when writing to cache
     * @return  Array|Resource
     */
    public function fetch(Action $action, Ttl $ttl)
    {
        if ($ttl->isNull()) {
            $ttl = $this->ttl;
        }

        $key = $this->getKey($action);

        $value = $this->cache->get($key);

        if ($value === null) {
            $value = $this->client->request($action);
            $this->cache->set($key, $value, $ttl->toSeconds());
        }

        return $value;
    }

    /**
     * Returns a unique cache key for an action.
     *
     * @return  string
     */
    private function getKey(Action $action): string
    {
        // Start with the action's resource name and parameters array.
        $prefixes = [$action->getResource()];
        $infixes  = [];
        $suffixes = [json_encode($action->getParams(), JSON_THROW_ON_ERROR)];

        // Add in the unique identifier of show actions.
        if ($action instanceof Show) {
            $infixes = [$action->getId()];
        }

        $segments = array_merge($prefixes, $infixes, $suffixes);
        $slug     = implode('/', $segments);

        // Hash the slug to produce a key of known length and valid characters.
        $hash = md5($slug);

        // Prefix the key to prevent (unlikely) collisions with other libraries.
        $key = "jahuty_{$hash}";

        return $key;
    }
}
