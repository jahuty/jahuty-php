<?php

namespace Jahuty\Api;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\{
    RequestInterface as Request,
    ResponseInterface as Response
};

class Client
{
    private $client;

    private $headers = [
        'Accept'          => 'application/json;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Content-Type'    => 'application/json; charset=utf-8',
        'Authorization'   => null,
        'User-Agent'      => null
    ];

    public function __construct(string $key)
    {
        $this->setHeaders($key);
    }

    public function send(Request $request): Response
    {
        if (null === $this->client) {
            $this->client = new HttpClient(['headers' => $this->headers]);
        }

        return $this->client->send($request);
    }

    private function setHeaders(string $key): void
    {
        $this->headers['Auhorization'] = "Bearer {$key}";
        $this->headers['User-Agent']   = 'Jahuty PHP SDK v' . \Jahuty\Jahuty::VERSION;
    }
}
