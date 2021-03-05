<?php

namespace Jahuty\Resource;

interface Resource
{
    /**
     * Constructs a resource from an API response.
     *
     * WARNING! Be certain this method silently ignores extra payload
     * attributes. Otherwise, adding attributes to our API responses will break
     * our SDKs.
     *
     * @param   array  $payload  the parsed JSON response
     * @return  Resource
     */
    public static function from(array $payload);
}
