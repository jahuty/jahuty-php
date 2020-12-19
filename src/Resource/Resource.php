<?php

namespace Jahuty\Resource;

abstract class Resource
{
    abstract public static function from(array $payload): Resource;
}
