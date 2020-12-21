<?php

namespace Jahuty\Service;

use Jahuty\Client;

/**
 * Factory for API resource services.
 *
 * @property  Snippet  $snippets
 */
class Factory
{
    private static $classes = [
        'snippets' => Snippet::class
    ];

    private $client;

    private $services = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __get(string $name): Service
    {
        if (null === ($class = $this->getServiceClass($name))) {
            throw new \OutOfBoundsException("Service '$name' not found");
        }

        if (!\array_key_exists($name, $this->services)) {
            $this->services[$name] = new $class($this->client);
        }

        return $this->services[$name];
    }

    private function getServiceClass(string $name): ?string
    {
        return \array_key_exists($name, self::$classes) ? self::$classes[$name]: null;
    }
}
