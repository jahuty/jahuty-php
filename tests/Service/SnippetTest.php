<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
use Jahuty\Cache\Ttl;
use Jahuty\Client;
use Jahuty\Resource\Render;

class SnippetTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderWhenParamsDoNotExist(): void
    {
        $action = new Show('render', 1);

        $render = new Render(1, 'foo');

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->isInstanceOf(Ttl::class))
            ->will($this->returnValue($render));

        (new Snippet($client))->render(1);
    }

    public function testRenderWhenParamsDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $render = new Render(1, 'foo');

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->isInstanceOf(Ttl::class))
            ->will($this->returnValue($render));

        (new Snippet($client))->render(1, ['params' => ['foo' => 'bar']]);
    }

    public function testRenderWhenParamsAndTllDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $render = new Render(1, 'foo');

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($action), $this->equalTo(new Ttl(60)))
            ->will($this->returnValue($render));

        (new Snippet($client))->render(1, [
            'params' => ['foo' => 'bar'],
            'ttl'    => 60
        ]);
    }

    public function testRenders(): void
    {
        $action = new Index('render', ['tag' => 'foo']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue([]));

        (new Snippet($client))->renders('foo');
    }
}
