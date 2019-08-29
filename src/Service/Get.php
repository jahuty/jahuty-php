<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Snippet\Service;

use GuzzleHttp\Client;
use Jahuty\Snippet\Data\{Problem, Request, Snippet};
use Jahuty\Snippet\Exception\NotOk;

class Get
{
    private $client;

    private $key;

    public function __construct(string $key, Client $client)
    {
        $this->key    = $key;
        $this->client = $client;
    }

    public function __invoke(int $id): Snippet
    {
        $request = new Request($this->key, $id);

        $response = $this->client->send($request);

        $payload = $response->getBody();
        $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        if ($response->getStatusCode() !== 200) {
            throw new NotOk(Problem::from($payload));
        }

        return Snippet::from($payload);
    }
}
