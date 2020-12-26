<?php

namespace Jahuty\Cache;

use Psr\SimpleCache\CacheInterface;
use Jahuty\Exception\Cache as InvalidArgumentException;

class Memory implements CacheInterface
{
    private $values = [];

    public function clear()
    {
        $this->values = [];

        return true;
    }

    public function delete($key)
    {
        if (!\is_string($key)) {
            throw new InvalidArgumentException(
                "Parameter one, key, is not a string"
            );
        }

        if ($this->has($key)) {
            unset($this->values[$key]);
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        if (!\is_array($keys)) {
            throw new InvalidArgumentException(
                "Parameter one, keys, must be an array"
            );
        }

        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function get($key, $default = null)
    {
        if (!\is_string($key)) {
            throw new InvalidArgumentException(
                "Parameter one, key, must be a string"
            );
        }

        if (!$this->has($key)) {
            return $default;
        }

        return $this->values[(string)$key];
    }

    public function getMultiple($keys, $default = null)
    {
        if (!\is_array($keys)) {
            throw new InvalidArgumentException(
                "Parameter one, keys, must be an array"
            );
        }

        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    public function has($key)
    {
        if (!\is_string($key)) {
            throw new InvalidArgumentException(
                "Parameter one, key, must be a string"
            );
        }
        return \array_key_exists($key, $this->values);
    }

    public function set($key, $value, $ttl = null)
    {
        if (!\is_string($key)) {
            throw new InvalidArgumentException(
                "Parameter one, key, must be a string"
            );
        }

        $this->values[$key] = $value;

        return true;
    }

    public function setMultiple($values, $ttl = null)
    {
        if (!\is_array($values)) {
            throw new InvalidArgumentException(
                "Parameter one, values, must be an array"
            );
        }

        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }
}