<?php

namespace Jahuty\Snippet\Exception;

use Jahuty\Snippet\Data\Problem;
use PHPUnit\Framework\TestCase;

class NotOkTest extends TestCase
{
    public function testGetProblem(): void
    {
        $problem = $this->createMock(Problem::class);

        $this->assertSame($problem, (new NotOk($problem))->getProblem());
    }
}
