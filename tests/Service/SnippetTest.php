<?php

namespace Jahuty\Service;

use Jahuty\Action\{Index, Show};
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

    public function testRenders(): void
    {
        $action = new Index('render', ['tag' => 'foo']);

        // Without a valid return value, a TypeError is raised. It is not clear
        // to me why only this test, and not the others included in this file,
        // requires a return value.
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action))
            ->will($this->returnValue([]));

        (new Snippet($client))->renders('foo');
    }
}
