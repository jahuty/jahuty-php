<?php

namespace Jahuty\Api;

class Response
{
    private $body;

    private $headers = [];

    private $status;

    public function __construct(int $status, array $body, array $headers = [])
    {
        $this->status = $status;
        $this->body   = $body;

        $this->setHeaders($headers);
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getHeader(string $name): array
    {
        if (!$this->hasHeader($name)) {
            throw new \OutOfBoundsException("Header '$name' does not exist");
        }

        return $this->headers[strtolower($name)];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function hasHeader(string $name): bool
    {
        return \array_key_exists(strtolower($name), $this->headers);
    }

    public function isError(): bool
    {
        return $this->status >= 400;
    }

    public function isProblem(): bool
    {
        return $this->isError() &&
            $this->getHeader('Content-Type') === ['application/problem+json'];
    }

    public function isSuccess(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    private function setHeaders(array $headers): self
    {
        $headers = array_change_key_case($headers, CASE_LOWER);

        $headers = array_map(function ($value) {
            return array_map('strtolower', $value);
        }, $headers);

        $this->headers = $headers;

        return $this;
    }
}
