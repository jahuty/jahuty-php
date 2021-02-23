<?php

namespace Jahuty\Collection;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testGetResources(): void
    {
        $this->assertEquals([], (new Collection([]))->getResources());
    }
}
