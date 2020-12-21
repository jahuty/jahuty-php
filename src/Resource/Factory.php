<?php

namespace Jahuty\Resource;

use Jahuty\Action\Action;
use Psr\Http\Message\ResponseInterface as Response;

class Factory
{
    private static $classes = [
        'render'  => Render::class,
        'problem' => Problem::class
    ];

    public function new(Action $action, Response $response): Resource
    {
        if ($this->isSuccess($response)) {
            $name = $action->getResource();
        } elseif ($this->isProblem($response)) {
            $name = 'problem';
        } else {
            throw new \OutOfBoundsException("Unexpected response");
        }

        $class   = $this->getResourceClass($name);
        $payload = $this->parse($response->getBody());

        return $class::from($payload);
    }

    private function getResourceClass(string $name): ?string
    {
        if (!\array_key_exists($name, self::$classes)) {
            throw new \OutOfBoundsException("Resource '$name' not found");
        }

        return self::$classes[$name];
    }

    private function isSuccess(Response $response): bool
    {
        return $response->getStatusCode() >= 200 &&
            $response->getStatusCode() < 300;
    }

    private function isProblem(Response $response): bool
    {
        return $response->getHeaderLine('Content-Type') === 'application/problem+json';
    }

    private function parse(string $body): array
    {
        return \json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
