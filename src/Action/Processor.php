<?php

namespace Jahuty\Action;

use Jahuty\Api\Request;

class Processor
{
    private $router;

    public function process(Action $action): Request
    {
        return new Request(
            $this->getMethod(),
            $this->getPath($action),
            $this->getOptions($action)
        );
    }

    private function getMethod(): string
    {
        return 'get';
    }

    private function getPath(Action $action): string
    {
        if (null === $this->router) {
            $this->router = new Router();
        }

        return $this->router->route($action);
    }

    private function getOptions(Action $action): array
    {
        $options = [];

        if ($action->hasParams()) {
            $options['query'] = $action->getParams();
        }

        return $options;
    }
}
