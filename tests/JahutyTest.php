<?php

namespace Jahuty\Jahuty;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class JahutyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->reset();
    }

    protected function tearDown(): void
    {
        $this->reset();
    }

    public function testGetClient(): void
    {
        $this->assertInstanceOf(Client::class, Jahuty::getClient());
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
     * Reset Jahuty's static state around tests.
     *
     * PHPUnit 9.0+ recommends explicitly resetting the values of static
     * properties between tests in setup() and tearDown() methods, because the
     * @backupStaticAttributes annotation (and XML configuration setting of the
     * same name) don't work on newly loaded classes.
     *
     * @see  https://phpunit.readthedocs.io/en/9.0/fixtures.html
     */
    private function reset(): void
    {
        $staticProperties = [
            "client" => null,
            "key"    => null
        ];

        $reflection = new ReflectionClass(Jahuty::class);

        foreach ($staticProperties as $propertyName => $defaultValue) {
            $property = $reflection->getProperty($propertyName);

            $property->setAccessible(true);
            $property->setValue($defaultValue);
            $property->setAccessible(false);
        }
    }
}
