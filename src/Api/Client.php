<?php

namespace Jahuty\Api;

use GuzzleHttp\Client as HttpClient;

class Client
{
    private $key;

    private $client;

    private $config = [
        'api_key'  => null,
        'base_uri' => \Jahuty\Jahuty::BASE_URI
    ];

    private $headers = [
        'Accept'          => 'application/json;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Content-Type'    => 'application/json; charset=utf-8'
    ];

    public function __construct($config)
    {
        if (\is_string($config)) {
            $config = ['api_key' => $config];
        } elseif (!\is_array($config)) {
            throw new \InvalidArgumentException(
                "Parameter one, config, must be a string key or config array"
            );
        }

        $this->configure($config);
    }

    public function send(Request $request): Response
    {
        if (null === $this->client) {
            $this->client = new HttpClient([
                'headers'  => $this->getHeaders(),
                'base_uri' => $this->getBaseUri()
            ]);
        }

        $response = $this->client->request(
            $request->getMethod(),
            $request->getPath(),
            $request->getOptions()
        );

        return new Response(
            $response->getStatusCode(),
            $this->parse($response->getBody()),
            $response->getHeaders()
        );
    }

    private function getApiKey(): string
    {
        return $this->config['api_key'];
    }

    private function getBaseUri(): string
    {
        return $this->config['base_uri'];
    }

    private function getHeaders(): array
    {
         return \array_merge(
             $this->headers,
             [
                'Authorization' => "Bearer {$this->getApiKey()}",
                'User-Agent'    => 'Jahuty PHP SDK v' . \Jahuty\Jahuty::VERSION
             ]
         );
    }

    private function parse(string $body): array
    {
        return \json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    private function configure(array $config): self
    {
        $this->config = \array_merge($this->config, $config);

        if (empty($this->config['api_key'])) {
            throw new \InvalidArgumentException(
                "Config setting 'api_key' cannot be empty"
            );
        }

        if (empty($this->config['base_uri'])) {
            throw new \InvalidArgumentException(
                "Config setting 'base_uri' cannot be empty"
            );
        }

        return $this;
    }
}
