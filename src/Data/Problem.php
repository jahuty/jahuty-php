<?php
/**
 * @copyright  2019 Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Snippet\Data;

use BadMethodCallException;

/**
 * An application/problem+json API response.
 */
class Problem
{
    private $detail;

    private $type;

    private $status;

    public function __construct(int $status, string $type, string $detail)
    {
        $this->status = $status;
        $this->type   = $type;
        $this->detail = $detail;
    }

    public static function from(array $payload): Problem
    {
        if (!array_key_exists('status', $payload)) {
            throw new BadMethodCallException("Key 'status' does not exist");
        }

        if (!array_key_exists('type', $payload)) {
            throw new BadMethodCallException("Key 'type' does not exist");
        }

        if (!array_key_exists('detail', $payload)) {
            throw new BadMethodCallException("Key 'detail' does not exist");
        }

        return new Problem(
            $payload['status'],
            $payload['type'],
            $payload['detail']
        );
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
