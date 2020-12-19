<?php

namespace Jahuty;

class JahutyTest extends \PHPUnit\Framework\TestCase
{
    public function testBaseUriIsUrl(): void
    {
        $this->assertNotFalse(filter_var(Jahuty::BASE_URI, FILTER_VALIDATE_URL));
    }

    public function testBaseUriIsHttpsScheme(): void
    {
        $scheme = \parse_url(Jahuty::BASE_URI, PHP_URL_SCHEME);

        $this->assertEquals('https', $scheme);
    }

    public function testBaseUriIsJahutyHost(): void
    {
        $host = \parse_url(Jahuty::BASE_URI, PHP_URL_HOST);

        $this->assertStringEndsWith('jahuty.com', $host);
    }

    public function testVersion(): void
    {
        $this->assertTrue(is_string(Jahuty::VERSION));
    }
}
