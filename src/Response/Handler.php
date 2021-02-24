<?php

namespace Jahuty\Response;

use Jahuty\Action\Action;
use Jahuty\Resource;
use Psr\Http\Message\ResponseInterface as Response;

class Handler
{
    private $resources;

    public function handle(Action $action, Response $response): Resource\Resource
    {
        if ($this->isSuccess($response)) {
            $name = $action->getResource();
        } elseif ($this->isProblem($response)) {
            $name = 'problem';
        } else {
            throw new \OutOfBoundsException('Unexpected response');
        }

        $payload = $this->parse($response->getBody());

        if ($this->resources === null) {
            $this->resources = new Resource\Factory();
        }

        return $this->resources->create($name, $payload);
    }

    private function hasSuccessfulStatusCode(Response $response): bool
    {
        return $response->getStatusCode() >= 200 &&
            $response->getStatusCode() < 300;
    }

    private function hasJsonContentType(Response $response): bool
    {
        return \strpos(
            $response->getHeaderLine('Content-Type'),
            'application/json'
        ) !== false;
    }

    private function hasProblemJsonContentType(Response $response): bool
    {
        return \strpos(
            $response->getHeaderLine('Content-Type'),
            'application/problem+json'
        ) !== false;
    }

    private function isSuccess(Response $response): bool
    {
        return $this->hasSuccessfulStatusCode($response) &&
            $this->hasJsonContentType($response);
    }

    private function isProblem(Response $response): bool
    {
        return !$this->hasSuccessfulStatusCode($response) &&
            $this->hasProblemJsonContentType($response);
    }

    private function parse(string $body): array
    {
        return \json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
