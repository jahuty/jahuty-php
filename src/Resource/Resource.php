<?php

namespace Jahuty\Resource;

interface Resource
{
    /**
     * Make sure this method accepts unused parameters. Otherwise, we won't be
     * able to add attributes to our API responses without coordinating an SDK
     * release.
     */
    public static function from(array $payload);
}
