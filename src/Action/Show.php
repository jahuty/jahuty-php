<?php

namespace Jahuty\Action;

class Show extends Action
{
    private $id;

    public function __construct(string $resource, int $id, array $params = [])
    {
        parent::__construct($resource, $params);

        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
