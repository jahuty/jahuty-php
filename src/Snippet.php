<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty;

use BadMethodCallException;
use GuzzleHttp\Client;
use Jahuty\Jahuty\Data\Snippet as Resource;
use Jahuty\Jahuty\Service\Get;

/**
 * A static wrapper for the memoized service and API key.
 */
class Snippet
{
    private static $get;

    private static $key;

    public static function get(int $id): Resource
    {
        if (self::$key === null) {
            throw new BadMethodCallException(
                "API key not set. Did you call Snippet::key()?"
            );
        }

        if (self::$get === null) {
            self::$get = new Get(self::$key, new Client());
        }

        return (self::$get)($id);
    }

    public static function key(string $key): void
    {
        self::$key = $key;
    }
}
