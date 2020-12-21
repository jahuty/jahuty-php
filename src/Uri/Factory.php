<?php
namespace Jahuty\Uri;

use Jahuty\Action\Action;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

class Factory
{
    private static $paths = [
        'show' => [
            'render' => 'snippets/:id/render'
        ]
    ];

    private $baseUri;

    public function __construct(string $baseUri = \Jahuty\Jahuty::BASE_URI)
    {
        $this->baseUri = $baseUri;
    }

    public function new(Action $action): UriInterface
    {
        $path = $this->getPath($action);

        $uri  = $this->getUri($path, $action->getParams());

        return new Uri($uri);
    }

    private function getPath(Action $action): ?string
    {
        if (!\array_key_exists($action->getResource(), self::$paths['show'])) {
            throw new \OutOfBoundsException(
                "Resource '{$action->getResource()}' not found"
            );
        }

        $path = self::$paths['show'][$action->getResource()];

        $path = $this->setVar(':id', $action->getId(), $path);

        return $path;
    }

    private function getUri(string $path, array $params = []): string
    {
        $uri = "{$this->baseUri}/{$path}";

        if (!empty($params)) {
            $uri .= '?' . \http_build_query($params);
        }

        return $uri;
    }

    private function setVar(string $pattern, string $value, string $path): string
    {
        return \str_replace($pattern, $value, $path);
    }
}
