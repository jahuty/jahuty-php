<?php
namespace Jahuty\Uri;

use Jahuty\Action\{Action, Show};
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

class Factory
{
    private static $paths = [
        'index' => [
            'render' => 'snippets/render'
        ],
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

    private function getActionName(Action $action): string
    {
        $path = \get_class($action);
        $segments = \explode('\\', $path);
        $classname = \end($segments);

        return strtolower($classname);
    }

    private function getPath(Action $action): ?string
    {
        $name = $this->getActionName($action);

        if (!\array_key_exists($name, self::$paths)) {
            throw new \OutOfBoundsException("Action '$name' not found");
        }

        if (!\array_key_exists($action->getResource(), self::$paths[$name])) {
            throw new \OutOfBoundsException(
                "Resource '{$action->getResource()}' not found for action '$name'"
            );
        }

        $path = self::$paths[$name][$action->getResource()];

        $path = $this->setVars($action, $path);

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

    private function setVars(Action $action, string $path): string
    {
        if ($action instanceof Show) {
            $path = $this->setVar(':id', $action->getId(), $path);
        }

        return $path;
    }
}
