[![CircleCI](https://circleci.com/gh/jahuty/jahuty-php.svg?style=svg)](https://circleci.com/gh/jahuty/jahuty-php)

# jahuty-php

Welcome to [Jahuty's](https://www.jahuty.com) server-side PHP SDK!

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

## Configuration

Configure `Jahuty` with your [API key](https://www.jahuty.com/docs/api#authentication) (ideally, once during startup):

```php
use Jahuty\Jahuty\Jahuty;

Jahuty::setKey('YOUR_API_KEY');
```

## Usage

With the API key set, you can use the `get()` method to retrieve a snippet:

```php
use Jahuty\Jahuty\Snippet;

// retrieve the snippet...
$snippet = Snippet::get(YOUR_SNIPPET_ID);

// .. and, cast it to a string...
(string)$snippet;

// ...or, access its attributes
$snippet->getId();
$snippet->getContent();
```

In an HTML view:

```html+php
<?php
use Jahuty\Jahuty\Jahuty;
use Jahuty\Jahuty\Snippet;

Jahuty::setKey('YOUR_API_KEY');
?>
<!doctype html>
<html>
<head>
    <title>Awesome example</title>
</head>
<body>
    <?php echo Snippet::get(YOUR_SNIPPET_ID); ?>
</body>
```

If you don't set your API key before calling `Snippet::get()`, a `BadMethodCallException` will be thrown. If an error occurs with [Jahuty's API](https://www.jahuty.com/docs/api), a `NotOk` exception will be thrown:

```php
use Jahuty\Jahuty\Snippet;
use Jahuty\Jahuty\Exception\NotOk;

try {
  Snippet::(YOUR_SNIPPET_ID);
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

If you spot an error, please open an issue or [let us know](https://www.jahuty.com/contacts/new)!
