<?php

namespace Jahuty\Service;

use Jahuty\Action\Show;
use Jahuty\Resource\Resource;

class Snippet extends Service
{
    public function render(int $id, array $options = []): Resource
    {
        $params = [];
        
        if (\array_key_exists('params', $options)) {
            $params['params'] = \json_encode($options['params'], JSON_THROW_ON_ERROR);
        }

        $action = new Show('render', $id, $params);

        return $this->client->request($action);
    }
}
