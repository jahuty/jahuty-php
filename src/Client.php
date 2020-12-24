<?php

namespace Jahuty;

/**
 * Sends requests to Jahuty's API.
 *
 * @property  Service\Snippet  $snippets
 */
class Client
{
    private $client;

    private $key;

    private $requests;

    private $resources;

    private $options = [
        'cache' => null,
        'ttl'   => 60
    ];

    private $services;

    public function __construct(string $key, array $options = [])
    {
        $this->key = $key;

        $this->setOptions($options);
    }

    public function __get(string $name): Service\Service
    {
        if (null === $this->services) {
            $this->services = new Service\Factory($this);
        }

        return $this->services->$name;
    }

    public function request(Action\Action $action): Resource\Resource
    {
        if (null === $this->requests) {
            $this->requests = new Request\Factory();
        }

        $request = $this->requests->new($action);

        if (null === $this->client) {
            $this->client = new Api\Client($this->key);
        }

        $response = $this->client->send($request);

        if (null === $this->resources) {
            $this->resources = new Resource\Factory();
        }

        $resource = $this->resources->new($action, $response);

        if ($resource instanceof Resource\Problem) {
            throw new Exception\Error($resource);
        }

        return $resource;
    }

    private function setOptions(array $options): void
    {
        $options = \array_merge($this->options, $options);

        if (
            $options['cache'] !== null &&
            ! $options['cache'] instanceof CacheInterface
        ) {
            throw new \InvalidArgumentException(
                "Option 'cache' must be null or CacheInterface"
            );
        }

        if ($options['ttl'] !== null &&
            (int)$options['ttl'] !== $options['ttl'] &&
            ! $options['ttl'] instanceof \DateInterval
        ) {
            throw new \InvalidArgumentException(
                "Option 'ttl' must be null, integer, or DateInterval"
            );
        }

        $this->options = $options;
    }
}
