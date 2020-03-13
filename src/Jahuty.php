<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Jahuty;

use GuzzleHttp\Client;

/**
 * Store version, key, and client.
 */
class Jahuty
{
    public const VERSION = "3.1.0";

    private const HEADERS = [
        'Accept'          => 'application/json;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Content-Type'    => 'application/json; charset=utf-8',
        'User-Agent'      => "Jahuty PHP SDK v{self::VERSION}"
    ];

    private const BASE_URI = 'https://www.jahuty.com/api/';

    private static $client;

    private static $key;

    public static function getClient(): Client
    {
        if (self::$client === null) {
            self::$client = new Client([
                'headers'  => self::headers(),
                'base_uri' => self::BASE_URI
            ]);
        }

        return self::$client;
    }

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

    private static function headers(): array
    {
        $headers = self::HEADERS;

        $headers['Authorization'] = 'Bearer '. self::$key;

        return $headers;
    }
}
