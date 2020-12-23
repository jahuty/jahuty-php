<?php

namespace Jahuty\Exception;

use Jahuty\Resource\Problem;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testGetProblem(): void
    {
        $problem = $this->createMock(Problem::class);

        $this->assertSame($problem, (new Error($problem))->getProblem());
    }
}
