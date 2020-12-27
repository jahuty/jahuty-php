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

Instantiate the library with your [API key](https://docs.jahuty.com/api#authentication) and use the `snippets->render()` method to render your snippet:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$render = $jahuty->snippets->render(YOUR_SNIPPET_ID);
```

Then, output the render's content by casting it to a `string` or using its `getContent()` method:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$render = $jahuty->snippets->render(YOUR_SNIPPET_ID);

echo $render;
echo $render->getContent();
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

Caching controls how frequently this library requests content from Jahuty's API.

* In _development_, where content is frequently changing and low traffic, you can use the default in-memory store to view content changes instantaneously.
* In _production_, where content is more stable and high traffic, you can configure persistent caching to reduce network requests and improve your application's response time.

### Caching in memory (default)

By default, Jahuty uses an in-memory cache to avoid requesting the same render more than once during the same request lifecycle. For example:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

// This call sends a synchronous API request; caches the result in memory; and,
// returns the result to the caller.
$render1 = $jahuty->snippets->render(YOUR_SNIPPET_ID);

// This call skips sending an API request and uses the cached value instead.
$render2 = $jahuty->snippets->render(YOUR_SNIPPET_ID);
```

The in-memory cache only persists for the duration of the original request, however. At the end of the request's lifecycle, the cache will be discarded. To store renders across requests, you need a persistent cache.

### Caching persistently

A persistent cache allows renders to be cached across multiple requests. This reduces the number of synchronous network requests to Jahuty's API and improves your application's average response time.

To configure Jahuty to use your persistent cache, pass a [PSR-16 `CacheInterface`](https://www.php-fig.org/psr/psr-16/) implementation to the client via the `cache` configuration option:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => $cache]);
```

The persistent cache implementation you choose and configure is up to you. There are many libraries available, and most frameworks provide their own. Any PSR-16 compatible implementation will work.

### Expiring

There are three methods for configuring a render's Time to Live (TTL), the amount of time between when a render is stored and when it's considered stale. From lowest-to-highest precedence, they are:

1. configuring your caching implementation,
1. configuring this library's default TTL, and
1. configuring a render's TTL.

#### Configuring your caching implementation

You can usually configure your caching implementation with a default TTL. If no other TTL is configured, this library will defer to the caching implementation's default TTL.

#### Configuring this library's default TTL

You can configure a default TTL for all of this library's renders by passing an integer number of seconds or a `DateInterval` instance via the client's `ttl` configuration option:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY', [
  'cache' => $cache,
  'ttl'   => 60  // <- cache all renders for sixty seconds
]);
```

If this library's default TTL is set, it will take precedence over the default TTL of the caching implementation.

#### Configuring a render's TTL

You can configure one render's TTL by passing an integer number of seconds or a `DateInterval` instance via its `ttl` configuration option:

```php
// default to the caching implementation's TTL for all renders
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => $cache]);

$render = $jahuty->snippets->render(1, [
  'ttl' => 60  // <- except this render
]);
```

If a render's TTL is set, it will take precedence over the library's default TTL and the caching implementation's TTL.

### Disabling caching

You can disable caching, even the default in-memory caching, by passing a `ttl` of zero (`0`) or a negative integer (e.g., `-1`) via any of the methods described above. For example:

```php
// disable all caching
$jahuty1 = new \Jahuty\Client('YOUR_API_KEY', ['ttl' => 0]);

// disable caching for this render
$jahuty2 = new \Jahuty\Client('YOUR_API_KEY');
$jahuty2->snippets->render(1, ['ttl' => 0]);
```

## Errors

If Jahuty's API returns any [status code](https://docs.jahuty.com/api) other than `2xx`, a `Jahuty\Exception\Error` will be thrown:

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
