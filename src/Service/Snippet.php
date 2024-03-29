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

    private $preferLatest;

    private $ttl;

    public function __construct(
        Client $client,
        CacheInterface $cache,
        Ttl $ttl,
        bool $preferLatest = false
    ) {
        parent::__construct($client);

        $this->cache = $cache;
        $this->ttl = $ttl;
        $this->preferLatest = $preferLatest;
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
     *   prefer_latest_content: bool  a flag indicating whether or not to render
     *     the latest content version instead of the published version (deprecated)
     *   prefer_latest:  bool  a flag indicating whether or not to render the
     *     latest content version instead of the published version
     * }
     * @return  array
     */
    public function allRenders(string $tag, array $options = []): array
    {
        [
            'ttl' => $ttl,
            'params' => $allParams,
            'prefer_latest' => $preferLatest
        ] = $this->unpackOptions($options);

        $requestParams = ['tag' => $tag];
        if ($allParams) {
            $requestParams['params'] = $this->encode($allParams);
        }
        if ($preferLatest) {
            $requestParams['latest'] = 1;
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
                $renderParams,
                $preferLatest
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
     *   prefer_latest_content: bool  a flag indicating whether or not to render
     *     the latest content version instead of the published version (deprecated)
     *   prefer_latest: bool  a flag indicating whether or not to render the
     *     latest content version instead of the published version
     *   location:  string  the render's current URL
     * }
     * @return  Resource
     */
    public function render(int $snippetId, array $options = []): Resource
    {
        [
            'ttl' => $ttl,
            'params' => $renderParams,
            'location' => $location,
            'prefer_latest' => $preferLatest
        ] = $this->unpackOptions($options);

        $cacheKey = $this->getCacheKey($snippetId, $renderParams, $preferLatest);

        if (null !== ($render = $this->cache->get($cacheKey))) {
            return $render;
        }

        $requestParams = [];
        if ($renderParams) {
            $requestParams['params'] = $this->encode($renderParams);
        }
        if ($preferLatest) {
            $requestParams['latest'] = 1;
        }
        if ($location) {
            $requestParams['location'] = $location;
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

    private function getCacheKey(int $snippetId, array $params = [], bool $latest = false): string
    {
        $slug = "snippets/{$snippetId}/render";

        if ($params) {
            $slug .= "/{$this->encode($params)}";
        }

        if ($latest) {
            $slug .= '/latest';
        }

        $hash = md5($slug);

        return "jahuty_{$hash}";
    }

    private function unpackOptions(array $options): array
    {
        // Handle the deprecated 'prefer_latest_content' option, if it exists.
        if (\array_key_exists('prefer_latest_content', $options)) {
            $options['prefer_latest'] = $options['prefer_latest_content'];
            unset($options['prefer_latest_content']);
        }

        // Wrap a TTL in an object, if it exists.
        if (\array_key_exists('ttl', $options)) {
            $options['ttl'] = new Ttl($options['ttl']);
        }

        $defaults = [
            'params' => [],
            'ttl' => $this->ttl,
            'location' => null,
            'prefer_latest' => $this->preferLatest
        ];

        return \array_merge($defaults, $options);
    }
}
