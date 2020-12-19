[![CircleCI](https://circleci.com/gh/jahuty/jahuty-php.svg?style=svg)](https://circleci.com/gh/jahuty/jahuty-php)

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

Before use, the library needs to be configured with your [API key](https://docs.jahuty.com/api#authentication) (ideally, once during startup):

```php
use Jahuty\Jahuty\Jahuty;

Jahuty::setKey('YOUR_API_KEY');
```

With the API key set, you can use the `Snippet::render()` method to render a snippet:

```php
use Jahuty\Jahuty\Snippet;

// render the snippet...
$render = Snippet::render(YOUR_SNIPPET_ID);

// .. and, cast it to a string...
(string)$render;

// ...or, access its content
$render->getContent();
```

In an HTML view:

```html+php
<?php
use Jahuty\Jahuty\{Jahuty, Snippet};

Jahuty::setKey('YOUR_API_KEY');
?>
<!doctype html>
<html>
<head>
    <title>Awesome example</title>
</head>
<body>
    <?php echo Snippet::render(YOUR_SNIPPET_ID); ?>
</body>
```

## Parameters

You can [pass parameters](https://docs.jahuty.com/liquid/parameters) into your snippet using the optional options hash and the `params` key:

```php
use Jahuty\Jahuty\Snippet;

$render = Snippet::render(YOUR_SNIPPET_ID, ['params' => ['foo' => 'bar']]);
```

The parameters above would be equivalent to [assigning the variables](https://docs.jahuty.com/liquid/variables) below in your snippet:

```html
{% assign foo = "bar" %}
```

## Errors

If you don't set your API key before calling `Snippet::render()`, a `BadMethodCallException` will be thrown, and if [Jahuty's API](https://docs.jahuty.com/api) returns any status code other than `2xx`, a `NotOk` exception will be thrown:

```php
use Jahuty\Jahuty\Snippet;
use Jahuty\Jahuty\Exception\NotOk;

try {
  $render = Snippet::render(YOUR_SNIPPET_ID);
} catch (BadMethodCallException $e) {
  // hmm, did you call Jahuty::setKey() first?
} catch (NotOk $e) {
  // hmm, the API returned something besides 2xx status code
  $problem = $e->getProblem();

  echo $problem->getStatus();  // returns status code
  echo $problem->getType();    // returns URL to more information
  echo $problem->getDetail();  // returns description of error
}
```

That's it!

## License

This library is licensed under the [MIT license](LICENSE).

## Compliance

This library strives to adhere to the following standards:

1. [Keep a Changelog 1.0](http://keepachangelog.com/en/1.0.0/)
2. [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
5. [Semantic Versioning 2.0](http://semver.org/spec/v2.0.0.html)

If you spot an error, please open an issue or [let us know](https://www.jahuty.com/contact)!
