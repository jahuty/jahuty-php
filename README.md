[![CircleCI](https://circleci.com/gh/jahuty/jahuty-php.svg?style=svg)](https://circleci.com/gh/jahuty/jahuty-php) [![codecov](https://codecov.io/gh/jahuty/jahuty-php/branch/master/graph/badge.svg?token=XELPI4FWMI)](https://codecov.io/gh/jahuty/jahuty-php)

# jahuty-php

Welcome to the PHP SDK for [Jahuty's API](https://docs.jahuty.com/api)!

## Installation

This library requires [PHP 7.3+](https://secure.php.net).

It is multi-platform, and we strive to make it run equally well on Windows, Linux, and OSX.

It should be installed via [Composer](https://getcomposer.org). To do so, add the following line to the `require` section of your `composer.json` file, and run `composer update`:

```javascript
{
   "require": {
       "jahuty/jahuty-php": "^5.3"
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

You can also use [tags](https://docs.jahuty.com/components/tags) to select a collection of snippets with the `snippets->allRenders()` method:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$renders = $jahuty->snippets->allRenders('YOUR_TAG');

foreach ($renders as $render) {
  echo $render;
}
```

## Content versions

By default, this library will render a snippet's _published_ content. If you'd like to render the latest _staged_ content instead, you can use the `prefer_latest_content` configuration option at the client or render level:

```php
// Prefer the latest content for all renders.
$jahuty = new \Jahuty\Client('YOUR_API_KEY', [
  'prefer_latest_content' => true
]);
```

```php
// Use the default published content for all renders.
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

// And, render the latest content for this one.
$render = $jahuty->snippets->render(YOUR_SNIPPET_ID, [
  'prefer_latest_content' => true
]);
```

## Parameters

You can pass [parameters](https://docs.jahuty.com/liquid/parameters) into your renders using the `params` configuration option:

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

If you're rendering a collection of snippets, the first dimension of the `params` key determines the parameters' scope. Use the asterisk key (`*`) to pass the same parameters to all snippets, or use the snippet id as key to pass parameters to a specific snippet.

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$renders = $jahuty->snippets->allRenders('YOUR_TAG', [
  'params' => [
    '*' => [
      'foo' => 'bar'
    ],
    1 => [
      'baz' => 'qux'
    ]
  ]
]);
```

The two parameter lists will be recursively merged, and the snippet's parameters will take precedence. In the example below, the variable `foo` will be assigned the value `"bar"` for all snippets, except for snippet 1, where it will be assigned the value `"qux"`:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$renders = $jahuty->snippets->allRenders('YOUR_TAG', [
  'params' => [
    '*' => [
      'foo' => 'bar'
    ],
    1 => [
      'foo' => 'qux'
    ]
  ]
]);
```

## Tracking renders (Unreleased)

You can use the `render()` method's `location` configuration option to report the absolute URL where the snippet is being rendered. This helps your team preview their changes, and it helps you find and replace deprecated snippets.

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

$render = $jahuty->snippets->render(YOUR_SNIPPET_ID, [
  'location' => 'https://example.com'
]);
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
// returns the resulting render.
$render1 = $jahuty->snippets->render(YOUR_SNIPPET_ID);

// This call skips sending an API request and uses the cached render instead.
$render2 = $jahuty->snippets->render(YOUR_SNIPPET_ID);
```

The in-memory cache only persists for the duration of the original request, however. At the end of the request's lifecycle, the cache will be discarded. To store renders across requests, you need a persistent cache.

### Caching persistently

A persistent cache allows renders to be cached across multiple requests. This reduces the number of synchronous network requests to Jahuty's API and improves your application's response time.

To configure a persistent cache, pass a [PSR-16 `CacheInterface`](https://www.php-fig.org/psr/psr-16/) implementation to the client via the `cache` configuration option:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => CACHE_INSTANCE]);
```

The persistent cache implementation you choose and configure is up to you. There are many libraries available, and most frameworks provide their own. Any PSR-16 compatible implementation will do.

### Expiring

There are three methods for configuring a render's Time to Live (TTL), the amount of time between when a it is stored and when it's considered stale. From lowest-to-highest precedence, the methods are:

1. configuring your caching implementation,
1. configuring this library's default TTL, and
1. configuring a render's TTL.

#### Configuring your caching implementation

You can usually configure your caching implementation with a default TTL. If no other TTL is configured, this library will defer to the caching implementation's default TTL. How exactly you configure this depends on the library you choose.

#### Configuring this library's default TTL

You can configure a default TTL for all of this library's renders by passing an integer number of seconds or a `DateInterval` instance via the client's `ttl` configuration option:

```php
// Cache all renders for sixty seconds.
$jahuty = new \Jahuty\Client('YOUR_API_KEY', [
  'cache' => $cache,
  'ttl'   => 60
]);
```

If this library's default TTL is set, it will take precedence over the default TTL of the caching implementation.

#### Configuring a render's TTL

You can configure one render's TTL by passing an integer number of seconds or a `DateInterval` instance via its `ttl` configuration option:

```php
// Use the caching implementation's TTL for all renders.
$jahuty = new \Jahuty\Client('YOUR_API_KEY', ['cache' => $cache]);

// But, cache this render for 60 seconds.
$render = $jahuty->snippets->render(1, [
  'ttl' => 60
]);
```

If a render's TTL is set, it will take precedence over the library's default TTL and the caching implementation's TTL.

### Caching collections

By default, this library will cache each render returned by `allRenders()`:

```php
$jahuty = new \Jahuty\Client('YOUR_API_KEY');

// This call sends a network request, caches each render, and returns the
// collection.
$jahuty->snippets->allRenders('YOUR_TAG');

// ... later in your application

// If this snippet exists in the previously rendered collection, the cached
// render will be used.
$render = $jahuty->snippets->render(YOUR_SNIPPET_ID);
```

This is a powerful feature. By using tags and the `allRenders()` method, you can render and cache the content of an entire application with a single network request.

Further, when `allRenders()` can be called periodically outside your request cycle (e.g., in a background job), you can turn your persistent cache into your content storage mechanism. You can render and cache your dynamic content as frequently as your like without any hit to your application's response time.

### Disabling caching

You can disable caching, even the default in-memory caching, by passing a `ttl` of zero (`0`) or a negative integer (e.g., `-1`) via any of the methods described above. For example:

```php
// Disable all caching
$jahuty1 = new \Jahuty\Client('YOUR_API_KEY', ['ttl' => 0]);

// Disable caching for this render
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
