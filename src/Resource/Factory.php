<?php

namespace Jahuty\Resource;

class Factory
{
    private static $classes = [
        'render'  => Render::class,
        'problem' => Problem::class
    ];

    public function create(string $name, array $payload): Resource
    {
        if (null === ($class = $this->getResourceClass($name))) {
            throw new \OutOfBoundsException("Resource '$name' not found");
        }

        return $class::from($payload);
    }

    private function getResourceClass(string $name): ?string
    {
        return \array_key_exists($name, self::$classes) ? self::$classes[$name] : null;
    }
}
