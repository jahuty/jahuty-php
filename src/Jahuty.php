<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty;

/**
 * Store version and key.
 */
class Jahuty
{
    public const VERSION = "3.0.0";

    private static $key;

    public static function getKey(): string
    {
        return self::$key;
    }

    public static function hasKey(): bool
    {
        return self::$key !== null;
    }

    public static function setKey(string $key): void
    {
        self::$key = $key;
    }
}
