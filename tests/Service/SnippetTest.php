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
            ->method('request')
            ->with($this->equalTo($action));

        (new Snippet($client))->render(1);
    }

    public function testRenderWhenParamsDoExist(): void
    {
        $action = new Show('render', 1, ['params' => '{"foo":"bar"}']);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with($this->equalTo($action));

        (new Snippet($client))->render(1, ['params' => ['foo' => 'bar']]);
    }
}
