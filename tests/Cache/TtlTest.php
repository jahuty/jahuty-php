<?php

namespace Jahuty\Cache;

class TtlTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructWhenValueIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Ttl('foo');
    }

    public function testConstructWhenValueIsNull(): void
    {
        $this->assertInstanceOf(Ttl::class, new Ttl(null));
    }

    public function testConstructWhenValueIsInt(): void
    {
        $this->assertInstanceOf(Ttl::class, new Ttl(1));
    }

    public function testConstructWhenValueIsDateInterval(): void
    {
        $this->assertInstanceOf(Ttl::class, new Ttl(new \DateInterval('PT1S')));
    }

    public function testIsNullReturnsTrueWhenNull(): void
    {
        $this->assertTrue((new Ttl(null))->isNull());
    }

    public function testIsNullReturnsFalseWhenNotNull(): void
    {
        $this->assertFalse((new Ttl(1))->isNull());
    }

    public function testToSecondsWhenValueIsNull(): void
    {
        $this->assertNull((new Ttl(null))->toSeconds());
    }

    public function testToSecondsWhenValueIsPositiveInt(): void
    {
        $this->assertEquals(1, (new Ttl(1))->toSeconds());
    }

    public function testToSecondsWhenValueIsZero(): void
    {
        $this->assertEquals(0, (new Ttl(0))->toSeconds());
    }

    public function testToSecondsWhenValueIsNegativeInt(): void
    {
        $this->assertEquals(-1, (new Ttl(-1))->toSeconds());
    }

    public function testToSecondsWhenValueIsDateInterval(): void
    {
        $this->assertEquals(1, (new Ttl(new \DateInterval('PT1S')))->toSeconds());
    }
}
