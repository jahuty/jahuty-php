<?php

namespace Jahuty\Service;

use Jahuty\Client;

abstract class Service
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
