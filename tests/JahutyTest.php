<?php

namespace Jahuty\Jahuty;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class JahutyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetJahuty();
    }

    protected function tearDown(): void
    {
        $this->resetJahuty();
    }

    public function testgetKey(): void
    {
        $key = "foo";

        Jahuty::setKey($key);

        $this->assertEquals($key, Jahuty::getKey());
    }

    public function testHasKeyReturnsFalseIfKeyDoesNotExist(): void
    {
        $this->assertFalse(Jahuty::hasKey());
    }

    public function testHasKeyReturnsTrueIfKeyDoesExist(): void
    {
        Jahuty::setKey("foo");

        $this->assertTrue(Jahuty::hasKey());
    }

    public function testSetKey(): void
    {
        $this->assertNull(Jahuty::setKey("foo"));
    }

    /**
     * Reset Jahuty's static state between tests.
     *
     * PHPUnit 9.0+ recommends explicitly resetting the values of static
     * properties between tests in setup() and tearDown() methods, because the
     * @backupStaticAttributes annotation (and XML configuration setting of the
     * same name) don't work on newly loaded classes.
     *
     * @see  https://phpunit.readthedocs.io/en/9.0/fixtures.html
     */
    private function resetJahuty(): void
    {
        $reflection = new ReflectionClass(Jahuty::class);

        $key = $reflection->getProperty('key');
        $key->setAccessible(true);
        $key->setValue(null, null);
        $key->setAccessible(false);
    }
}
