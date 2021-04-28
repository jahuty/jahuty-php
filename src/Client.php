<?php

namespace Jahuty;

use Psr\SimpleCache\CacheInterface;

/**
 * Sends requests to Jahuty's API.
 *
 * @property  Service\Snippet  $snippets
 */
class Client
{
    private $apiClient;

    private $apiKey;

    private $baseUri = Jahuty::BASE_URI;

    private $cache;

    private $preferLatestContent = false;

    private $requests;

    private $responses;

    private $services = [];

    private $ttl;

    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;

        $this->cache = new Cache\Memory();
        $this->ttl   = new Cache\Ttl(null);

        $this->unpackOptions($options);
    }

    public function __get(string $name): Service\Service
    {
        if ($name !== 'snippets') {
            throw new \OutOfBoundsException("Service '$name' not found");
        }

        if (!\array_key_exists($name, $this->services)) {
            $this->services[$name] = new Service\Snippet(
                $this,
                $this->cache,
                $this->ttl
            );
        }

        return $this->services[$name];
    }

    /**
     * Executes an action against the API and returns result.
     *
     * @param   Action\Action  $action
     * @return  array|Resource\Resource
     */
    public function request(Action\Action $action)
    {
        if (null === $this->requests) {
            $this->requests = new Request\Factory($this->baseUri);
        }

        $request = $this->requests->new($action);

        if (null === $this->apiClient) {
            $this->apiClient = new Api\Client($this->apiKey);
        }

        $response = $this->apiClient->send($request);

        if (null === $this->responses) {
            $this->responses = new Response\Handler();
        }

        $result = $this->responses->handle($action, $response);

        if ($result instanceof Resource\Problem) {
            throw new Exception\Error($result);
        }

        return $result;
    }

    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public function setPreferLatestContent(bool $preferLatestContent): self
    {
        $this->preferLatestContent = $preferLatestContent;

        return $this;
    }

    public function setTtl($ttl): self
    {
        $this->cache = new Cache\Ttl($ttl);

        return $this;
    }

    /**
     * Unpacks the options array into object properties.
     *
     * @param   array  $options
     *   @option  string  base_uri  the base uri of API requests (optional; if
     *     omitted, defaults to the value of Jahuty::BASE_URI constant)
     *   @option  CacheInterface  cache  the client's cache (optional; if
     *     ommitted, defaults to in-memory cache)
     *   @option  int|DateTime  ttl  the default time-to-live when writing
     *     to the cache (optional; if omitted, uses the method's $ttl argument
     *     or the cache's default setting, in that order)
     *   @option  bool  prefer_latest_content  a flag indicating whether or not
     *     to prefer the latest content version (optional; if omitted, defaults
     *     to published content version)
     * @return  void
     */
    private function unpackOptions(array $options): void
    {
        if (isset($options['base_uri'])) {
            $this->setBaseUri($options['base_uri']);
        }

        if (isset($options['cache'])) {
            $this->setCache($options['cache']);
        }

        if (isset($options['ttl'])) {
            $this->setTtl($options['ttl']);
        }

        if (isset($options['prefer_latest_content'])) {
            $this->setPreferLatestContent($options['prefer_latest_content']);
        }
    }
}
