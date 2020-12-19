<?php

namespace Jahuty\Action;

use Jahuty\Api\Request;

class ProcessorTest extends \PHPUnit\Framework\TestCase
{
    public function testProcessReturnsRequest(): void
    {
        $action = new Show('render', 1, ['bar' => 'baz']);

        $this->assertInstanceOf(
            Request::class,
            (new Processor())->process($action)
        );
    }
}
