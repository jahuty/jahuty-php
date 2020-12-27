<?php

namespace Jahuty\Service;

use Jahuty\Action\Show;
use Jahuty\Cache\Ttl;
use Jahuty\Client;

class SnippetTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderWhenParamsDoNotExist(): void
    {
        $action = new Show('render', 1);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->isInstanceOf(Ttl::class));

        (new Snippet($client))->render(1);
    }

    public function testRenderWhenParamsDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->isInstanceOf(Ttl::class));

        (new Snippet($client))->render(1, ['params' => ['foo' => 'bar']]);
    }

    public function testRenderWhenParamsAndTllDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->equalTo(new Ttl(60)));

        (new Snippet($client))->render(1, [
            'params' => ['foo' => 'bar'],
            'ttl'    => 60
        ]);
    }
}
