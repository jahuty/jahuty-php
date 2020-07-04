<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty\Service;

use GuzzleHttp\Client;
use Jahuty\Jahuty\Data\{Problem, Render as Resource};
use Jahuty\Jahuty\Exception\NotOk;

class Render
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(int $id, array $options = []): Resource
    {
        $settings = [];

        if (array_key_exists('params', $options)) {
            $settings['query'] = [
                'params' => json_encode($options['params'], JSON_THROW_ON_ERROR)
            ];
        }

        $response = $this->client->request('GET', "snippets/$id", $settings);

        $payload = $response->getBody();
        $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        if ($response->getStatusCode() !== 200) {
            throw new NotOk(Problem::from($payload));
        }

        return Resource::from($payload);
    }
}
