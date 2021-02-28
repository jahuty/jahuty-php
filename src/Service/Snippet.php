<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
use Jahuty\Resource\Resource;
use Jahuty\Cache\Ttl;

class Snippet extends Service
{
    /**
     * Renders a snippet.
     */
    public function render(int $id, array $options = []): Resource
    {
        $defaults = [
            'params' => null,
            'ttl'    => null
        ];

        $options = \array_merge($defaults, $options);

        $params = [];
        if ($options['params']) {
            $params['params'] = $this->encode($options['params']);
        }

        $action = new Show('render', $id, $params);
        $ttl    = new Ttl($options['ttl']);

        return $this->client->fetch($action, $ttl);
    }

    /**
     * Renders a group of snippets.
     */
    public function renders(string $tag, array $options = []): array
    {
        $defaults = [
            'params' => null,
            'ttl'    => null
        ];

        $options = \array_merge($defaults, $options);

        $params = ['tag' => $tag];
        if ($options['params']) {
            $params['params'] = $this->encode($options['params']);
        }

        $action = new Index('render', $params);
        $ttl    = new Ttl($options['ttl']);

        return $this->client->fetch($action, $ttl);
    }

    private function encode(array $params): string
    {
        return \json_encode($params, JSON_THROW_ON_ERROR);
    }
}
