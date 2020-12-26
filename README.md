[![CircleCI](https://circleci.com/gh/jahuty/jahuty-php.svg?style=svg)](https://circleci.com/gh/jahuty/jahuty-php) [![codecov](https://codecov.io/gh/jahuty/jahuty-php/branch/master/graph/badge.svg?token=XELPI4FWMI)](https://codecov.io/gh/jahuty/jahuty-php)

# jahuty-php

Welcome to the PHP SDK for [Jahuty's API](https://docs.jahuty.com/api)!

## Installation

This library requires [PHP 7.3+](https://secure.php.net).

It is multi-platform, and we strive to make it run equally well on Windows, Linux, and OSX.

It should be installed via [Composer](https://getcomposer.org). To do so, add the following line to the `require` section of your `composer.json` file (where `x` is the latest major version), and run `composer update`:

```javascript
{
   "require": {
       "jahuty/jahuty-php": "^x"
   }
}
```

## Usage

Instantiate the library with your [API key](https://docs.jahuty.com/api#authentication), and use the `render()` method to output your snippet:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

// render the snippet...
$render = $jahuty->snippets->render(YOUR_SNIPPET_ID);

// .. and, cast it to a string...
(string)$render;

// ...or, access its content
$render->getContent();
```

In an HTML view:

```html+php
<?php
  $jahuty = new \Jahuty\Client('YOUR_API_KEY');
?>
<!doctype html>
<html>
<head>
    <title>Awesome example</title>
</head>
<body>
    <?php echo $jahuty->snippets->render(YOUR_SNIPPET_ID); ?>
</body>
```

## Parameters

You can [pass parameters](https://docs.jahuty.com/liquid/parameters) into your snippet using the `params` key in the options associative array:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$render = $jahuty->snippets->render(YOUR_SNIPPET_ID, [
  'params' => [
    'foo' => 'bar'
  ]
]);
```

The parameters above would be equivalent to [assigning the variables](https://docs.jahuty.com/liquid/variables) below in your snippet:

```html
{% assign foo = "bar" %}
```

## Caching

Caching allows you to balance a snippet's retrieval speed and refresh rate. When caching is enabled and a valid cache entry exists, this library can use the cached version instead of requesting the latest version from the API. With caching, you can tune your application's performance based on your content's stability and traffic.

### Caching in memory (default)

By default, Jahuty uses an in-memory cache to avoid requesting the same snippet and parameter combination more than once during the request's lifecycle. For example:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

// This call sends an API request. The result is cached in memory and returned.
$render1 = $jahuty->snippets->render(YOUR_SNIPPET_ID);

// This call skips sending an API request and uses the cached value instead.
$render2 = $jahuty->snippets->render(YOUR_SNIPPET_ID);
```

This in-memory cache only persists for the duration of the original request, however. At the end of the request's lifecycle, the cache will be discarded.

### Caching persistently

A persistent cache, on the other hand, allows content to be cached across multiple requests. This reduces the number of network requests and increases your application's performance.

To configure Jahuty to use your persistent cache, pass a [PSR-16](https://www.php-fig.org/psr/psr-16/) `CacheInterface` implementation to the client via its `cache` configuration option:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => $cache]);
```

### Expiring

There are three methods for configuring the amount of time between when a snippet is stored and when it's considered stale (aka, Time to Live or TTL). From lowest-to-highest precedence, they are:

1. _Configuring your caching implementation_. Many implementations allow you to set a default TTL, which this library will use by default.
1. _Configuring this library's default TTL_. You can pass an integer number of seconds or a `DateInterval` instance via the client's `ttl` configuration option for all requests:
```php
// cache all snippets for sixty seconds
$jahuty = new \Jahuty\Client('YOUR_API_KEY', [
  'cache' => $cache,
  'ttl'   => 60
]);
```
1. _Configuring a snippet's `ttl`_. You can pass an integer number of seconds or a `DateInterval` instance via the render's `ttl` option for a specific snippet and parameter combination:
```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => $cache]);

// cache this snippet for sixty seconds
$render = $jahuty->snippets->render(1, ['ttl' => 60]);
```

### Disabling caching

You can disable caching, even the default in-memory caching, by passing a `ttl` of zero (`0`) via any of the methods described above. For example:

```php
// disable all caching
$jahuty1 = new \Jahuty\Client('YOUR_API_KEY', ['ttl' => 0]);

// use the default in-memory caching...
$jahuty2 = new \Jahuty\Client('YOUR_API_KEY');
// ... but disable caching for this snippet
$jahuty2->snippets->render(1, ['ttl' => 0]);
```

## Errors

If [Jahuty's API](https://docs.jahuty.com/api) returns any status code other than `2xx`, a `Jahuty\Exception\Error` will be thrown:

```php
try {
  $jahuty = new \Jahuty\Client('YOUR_API_KEY');
  $render = $jahuty->snippets->render(YOUR_SNIPPET_ID);
} catch (\Jahuty\Exception\Error $e) {
  // The API returned something besides 2xx status code. Access the problem
  // object for more information.
  $problem = $e->getProblem();

  echo $problem->getStatus();  // returns status code
  echo $problem->getType();    // returns URL to more information
  echo $problem->getDetail();  // returns description of error
}
```

That's it!

## License

This library is licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! If you spot an error, please open an issue or [let us know](https://www.jahuty.com/contact).
