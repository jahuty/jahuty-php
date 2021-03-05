<?php

namespace Jahuty\Response;

use Jahuty\Action\{Action, Index};
use Jahuty\Resource\{Factory, Resource};
use Psr\Http\Message\ResponseInterface as Response;

class Handler
{
    private $resources;

    public function handle(Action $action, Response $response)
    {
        if ($this->isUnexpected($response)) {
            throw new \OutOfBoundsException(
                'Response is unexpected and neither a success nor a problem'
            );
        }

        $name = $this->isSuccess($response) ? $action->getResource() : 'problem';

        $payload = $this->parse($response->getBody());

        if ($this->resources === null) {
            $this->resources = new Factory();
        }

        if ($this->isCollection($action)) {
            $result = $this->createCollection($name, $payload);
        } else {
            $result = $this->createResource($name, $payload);
        }

        return $result;
    }

    private function createCollection(string $name, array $payload): array
    {
        $resources = [];

        foreach ($payload as $item) {
            $resources[] = $this->createResource($name, $item);
        }

        return $resources;
    }

    private function createResource(string $name, array $payload): Resource
    {
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

    private function isCollection(Action $action): bool
    {
        return $action instanceof Index;
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

    private function isUnexpected(Response $response): bool
    {
        return !$this->isSuccess($response) && !$this->isProblem($response);
    }

    private function parse(string $body): array
    {
        return \json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
