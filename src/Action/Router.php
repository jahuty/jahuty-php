<?php
namespace Jahuty\Action;

use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

class Router
{
    private static $paths = [
        Show::class => [
            'render' => 'snippets/:id/render'
        ]
    ];

    public function route(string $baseUri, Action $action): UriInterface
    {
        if (null === ($path = $this->getPath($action))) {
            throw new \OutOfBoundsException("Path not found");
        }

        $path = $this->setVar(':id', $action->getId(), $path);

        $uri = "{$baseUri}/{$path}";

        if ($action->hasParams()) {
            $uri .= '?' . \http_build_query($action->getParams());
        }

        return new Uri($uri);
    }

    private function getPath(Action $action): ?string
    {
        $name = get_class($action);

        if (\array_key_exists($name, self::$paths)) {
            if (\array_key_exists($action->getResource(), self::$paths[$name])) {
                return self::$paths[$name][$action->getResource()];
            }
        }

        return null;
    }

    private function setVar(string $pattern, string $value, string $path): string
    {
        return \str_replace($pattern, $value, $path);
    }
}
