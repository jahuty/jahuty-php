<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty;

use BadMethodCallException;
use Jahuty\Jahuty\Data\Render as Resource;
use Jahuty\Jahuty\Service\Render;

/**
 * A static wrapper for the memoized service.
 */
class Snippet
{
    private static $get;

    public static function render(int $id, array $options = []): Resource
    {
        if (!Jahuty::hasKey()) {
            throw new BadMethodCallException(
                "API key not set. Did you call Jahuty::setKey()?"
            );
        }

        if (self::$get === null) {
            self::$get = new Render(Jahuty::getClient());
        }

        return (self::$get)($id, $options);
    }
}
