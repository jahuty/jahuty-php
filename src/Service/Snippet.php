<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
use Jahuty\Resource\Resource;
use Jahuty\Cache\Ttl;

class Snippet extends Service
{
    public function render(int $id, array $options = []): Resource
    {
        $defaults = [
            'params' => null,
            'ttl'    => null
        ];

        $options = \array_merge($defaults, $options);

        $params = [];
        if ($options['params']) {
            $params['params'] = \json_encode(
                $options['params'],
                JSON_THROW_ON_ERROR
            );
        }

        $action = new Show('render', $id, $params);
        $ttl    = new Ttl($options['ttl']);

        return $this->client->fetch($action, $ttl);
    }

    public function renders(string $tag): array
    {
        $action = new Index('render', ['tag' => $tag]);

        return $this->client->request($action);
    }
}
