<?php

namespace Jahuty\Request;

use Jahuty\Action\Action;
use Jahuty\Uri;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class Factory
{
    private $baseUri;

    private $uris;

    public function __construct(string $baseUri = \Jahuty\Jahuty::BASE_URI)
    {
        $this->baseUri = $baseUri;
    }

    public function createRequest(Action $action): RequestInterface
    {
        return new Request($this->getMethod(), $this->getUri($action));
    }

    private function getMethod(): string
    {
        return 'get';
    }

    private function getUri(Action $action): string
    {
        if (null === $this->uris) {
            $this->uris = new Uri\Factory($this->baseUri);
        }

        return $this->uris->createUri($action);
    }
}
