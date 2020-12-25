<?php
namespace Jahuty\Cache;

use Psr\SimpleCache\InvalidArgumentException;

class MemoryTest extends \PHPUnit\Framework\TestCase
{
    public function testClearReturnsTrue(): void
    {
        $key   = 'foo';
        $cache = new Memory();

        $cache->set($key, 1);

        $this->assertTrue($cache->has($key));

        $this->assertTrue($cache->clear());

        $this->assertFalse($cache->has($key));
    }

    public function testDeleteThrowsExceptionWhenKeyIsNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->delete(1);
    }

    public function testDeleteReturnsTrueWhenKeyIsString(): void
    {
        $key   = 'foo';
        $cache = new Memory();

        $cache->set($key, 1);

        $this->assertTrue($cache->has($key));

        $this->assertTrue($cache->delete($key));

        $this->assertFalse($cache->has($key));
    }

    public function testDeleteMultipleThrowsExceptionWhenKeysIsNotvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->deleteMultiple('foo');
    }

    public function testDeleteMultipleReturnsTrueWhenKeysIsValid(): void
    {
        $key   = 'foo';
        $keys  = [$key];
        $cache = new Memory();

        $cache->set($key, 1);

        $this->assertTrue($cache->has($key));

        $this->assertTrue($cache->deleteMultiple($keys));

        $this->assertFalse($cache->has($key));
    }

    public function testGetThrowsExceptionWhenKeyIsNotValid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->get(1);
    }

    public function testGetReturnsDefaultIfValueDoesNotExist(): void
    {
        $this->assertEquals('bar', (new Memory())->get('foo', 'bar'));
    }

    public function testGetReturnsValueIfValueDoesExist(): void
    {
        $key   = 'foo';
        $value = 1;
        $cache = new Memory();

        $cache->set($key, $value);

        $this->assertEquals($value, $cache->get($key));
    }

    public function testGetMultipleThrowsExceptionWhenKeysIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->getMultiple(1);
    }

    public function testGetMultipleReturnsDefaultWhenValuesNotCached(): void
    {
        $keys    = ['foo', 'bar'];
        $cache   = new Memory();
        $default = 'baz';

        $expected = ['foo' => 'baz', 'bar' => 'baz'];
        $actual   = $cache->getMultiple($keys, $default);

        $this->assertEquals($expected, $actual);
    }

    public function testGetMultiple(): void
    {
        $cache = new Memory();

        $cache->set('foo', 1);
        $cache->set('bar', 1);

        $expected = ['foo' => 1, 'bar' => 1];
        $actual   = $cache->getMultiple(['foo', 'bar']);

        $this->assertEquals($expected, $actual);
    }

    public function testHasThrowsExceptionIfKeyIsNotValid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->has(1);
    }

    public function testHasReturnsTrueWhenKeyDoesExist(): void
    {
        $cache = new Memory();

        $cache->set('foo', 1);

        $this->assertTrue($cache->has('foo'));
    }

    public function testHasReturnsFalseWhenKeyDoesNotExist(): void
    {
        $this->assertFalse((new Memory())->has('foo'));
    }

    public function testSetThrowsExceptionIfKeyNotValid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->set(1, 'foo');
    }

    public function testSet(): void
    {
        $cache = new Memory();

        $this->assertFalse($cache->has('foo'));

        $this->assertTrue($cache->set('foo', true));

        $this->assertTrue($cache->has('foo'));
    }

    public function testSetMultipleThrowsExceptionIfValuesIsNotValid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Memory())->setMultiple(1);
    }

    public function testSetMultiple(): void
    {
        $cache = new Memory();

        $this->assertFalse($cache->has('foo'));

        $this->assertTrue($cache->setMultiple(['foo' => true]));

        $this->assertTrue($cache->has('foo'));
    }
}
