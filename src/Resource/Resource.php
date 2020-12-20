<?php

namespace Jahuty\Resource;

interface Resource
{
    public static function from(array $payload): Resource;
}
