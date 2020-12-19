<?php

namespace Jahuty\Api;

class Request
{
    private $method;

    private $options = [];

    private $path;

    public function __construct(string $method, string $path, array $options = [])
    {
        $this->method  = $method;
        $this->path    = $path;
        $this->options = $options;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
