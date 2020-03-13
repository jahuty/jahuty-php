<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty;

use BadMethodCallException;
use Jahuty\Jahuty\Data\Snippet as Resource;
use Jahuty\Jahuty\Service\Get;

/**
 * A static wrapper for the memoized service.
 */
class Snippet
{
    private static $get;

    public static function get(int $id, array $params = []): Resource
    {
        if (!Jahuty::hasKey()) {
            throw new BadMethodCallException(
                "API key not set. Did you call Jahuty::setKey()?"
            );
        }

        if (self::$get === null) {
            self::$get = new Get(Jahuty::getClient());
        }

        return (self::$get)($id, $params);
    }
}
