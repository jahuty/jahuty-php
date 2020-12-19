<?php
namespace Jahuty\Action;

/**
 * Routes an action to a path.
 */
class Router
{
    private static $routes = [
        Show::class => [
            'render' => 'snippets/:id/render'
        ]
    ];

    public function route(Action $action): string
    {
        if (null === ($route = $this->getRoute($action))) {
            throw new \OutOfBoundsException("Route not found");
        }

        return $this->setVar(':id', $action->getId(), $route);
    }

    private function getRoute(Action $action): ?string
    {
        $name = get_class($action);

        if (\array_key_exists($name, self::$routes)) {
            if (\array_key_exists($action->getResource(), self::$routes[$name])) {
                return self::$routes[$name][$action->getResource()];
            }
        }

        return null;
    }

    private function setVar(string $pattern, string $value, string $route): string
    {
        return \str_replace($pattern, $value, $route);
    }
}
