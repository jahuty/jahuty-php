<?php

namespace Jahuty\Service;

use Jahuty\Client;
use Jahuty\Action\Show;

class SnippetTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderWhenParamsDoNotExist(): void
    {
        $action = new Show('render', 1);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->equalTo(null));

        (new Snippet($client))->render(1);
    }

    public function testRenderWhenParamsDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->equalTo(null));

        (new Snippet($client))->render(1, ['params' => ['foo' => 'bar']]);
    }

    public function testRenderWhenParamsAndTllDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->equalTo(60));

        (new Snippet($client))->render(1, [
            'params' => ['foo' => 'bar'],
            'ttl'    => 60
        ]);
    }
}
