<?php
/**
 * @copyright  Jack Clayton <jack@jahuty.com>
 * @license    MIT
 */

namespace Jahuty\Snippet\Data;

use GuzzleHttp\Psr7\Request as Base;

/**
 * An API request.
 */
class Request extends Base
{
    private const HEADERS = [
        'Accept'          => 'application/json;q=0.9,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Content-Type'    => 'application/json; charset=utf-8',
        'User-Agent'      => 'Jahuty PHP client'
    ];

    private const ORIGIN = 'https://www.jahuty.com';

    private const PATH = 'api/snippets';

    public function __construct(string $key, int $id)
    {
        return parent::__construct('GET', $this->url($id), $this->headers($key));
    }

    private function headers($key): array
    {
        $headers = self::HEADERS;

        $headers['Authorization'] = "Bearer {$key}";

        return $headers;
    }

    private function url($id): string
    {
        return implode('/', [self::ORIGIN, self::PATH, $id]);
    }
}
