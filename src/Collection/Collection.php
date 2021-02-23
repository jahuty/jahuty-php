<?php

namespace Jahuty\Collection;

class Collection
{
    private $resources;

    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function getResources(): array
    {
        return $this->resources;
    }
}
