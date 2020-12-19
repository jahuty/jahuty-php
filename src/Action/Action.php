<?php

namespace Jahuty\Action;

abstract class Action
{
    private $params = [];

    private $resource;

    public function __construct(string $resource, array $params = [])
    {
        $this->resource = $resource;
        $this->params   = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function hasParams(): bool
    {
        return !empty($this->params);
    }
}
