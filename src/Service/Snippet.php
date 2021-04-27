<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
use Jahuty\Resource\Resource;
use Jahuty\Cache\Ttl;
use Jahuty\Client;
use Psr\SimpleCache\CacheInterface;

class Snippet extends Service
{
    private $cache;

    private $ttl;

    public function __construct(Client $client, CacheInterface $cache, Ttl $ttl)
    {
        parent::__construct($client);

        $this->cache = $cache;
        $this->ttl   = $ttl;
    }

    /**
     * Renders a group of snippets.
     *
     * I'll always write to the cache. I'll send an API request to render the
     * snippets, cache the individual renders, and return the collection.
     *
     * @param   string  $tag
     * @param   array   $options {
     *   params: array  an array of parameters to pass to the renders, indexed
     *     by snippet id (use the "*" index to pass parameters to all snippets)
     *   ttl: int|DateTime  the time-to-live to use when writing to the cache
     * }
     * @return  array
     */
    public function allRenders(string $tag, array $options = []): array
    {
        [
            'ttl'    => $ttl,
            'params' => $allParams
        ] = $this->unpackOptions($options);

        $requestParams = ['tag' => $tag];
        if ($allParams) {
            $requestParams['params'] = $this->encode($allParams);
        }

        $action = new Index('render', $requestParams);

        $renders = $this->client->request($action);

        // write the renders to the cache
        foreach ($renders as $render) {
            $renderParams = \array_merge_recursive(
                $allParams['*'] ?? [],
                $allParams[$render->getSnippetId()] ?? []
            );
            $cacheKey = $this->getCacheKey(
                $render->getSnippetId(),
                $renderParams
            );
            $this->cache->set($cacheKey, $render, $ttl->toSeconds());
        }

        return $renders;
    }

    /**
     * Renders a snippet.
     *
     * I'll read and write to the cache. In the case of a cache hit, I'll return
     * that render. In the case of a cache miss, I'll send an API request to
     * render the snippet, then cache and return the value.
     *
     * @param  int    $snippetId  the snippet to render
     * @param  array  $options {
     *   params: array  an array of parameters to pass to the render
     *   ttl: int|DateTime  the time-to-live to use when writing to the cache
     *   latest: bool  a flag indicating whether or not to render the latest
     *     content version instead of the published version
     * }
     * @return  Resource
     */
    public function render(int $snippetId, array $options = []): Resource
    {
        [
            'ttl'    => $ttl,
            'params' => $renderParams,
            'latest' => $isLatest
        ] = $this->unpackOptions($options);

        $cacheKey = $this->getCacheKey($snippetId, $renderParams);

        if (null !== ($render = $this->cache->get($cacheKey))) {
            return $render;
        }

        $requestParams = [];
        if ($renderParams) {
            $requestParams['params'] = $this->encode($renderParams);
        }
        if ($isLatest) {
            $requestParams['latest'] = 1;
        }

        $action = new Show('render', $snippetId, $requestParams);

        $render = $this->client->request($action);

        $this->cache->set($cacheKey, $render, $ttl->toSeconds());

        return $render;
    }

    private function encode(array $params): string
    {
        return \json_encode($params, JSON_THROW_ON_ERROR);
    }

    private function getCacheKey(int $snippetId, ?array $params): string
    {
        $slug = "snippets/{$snippetId}/render";

        if ($params) {
            $slug .= "/{$this->encode($params)}";
        }

        $hash = md5($slug);

        return "jahuty_{$hash}";
    }

    private function unpackOptions(array $options): array
    {
        $defaults = [
            'params' => null,
            'ttl'    => null,
            'latest' => false
        ];

        $results = \array_merge($defaults, $options);

        $ttl = new Ttl($results['ttl']);
        if ($ttl->isNull()) {
            $ttl = $this->ttl;
        }

        $results['ttl'] = $ttl;

        return $results;
    }
}
