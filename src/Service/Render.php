<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty\Service;

use GuzzleHttp\Client;
use Jahuty\Jahuty\Data\{Problem, Request, Snippet};
use Jahuty\Jahuty\Exception\NotOk;

class Render
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(int $id, array $params = []): Snippet
    {
        $options = [];

        if ($params) {
            $options['query'] = [
                'params' => json_encode($params, JSON_THROW_ON_ERROR)
            ];
        }

        $response = $this->client->request('GET', "snippets/$id", $options);

        $payload = $response->getBody();
        $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        if ($response->getStatusCode() !== 200) {
            throw new NotOk(Problem::from($payload));
        }

        return Snippet::from($payload);
    }
}
