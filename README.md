# snippets-php

Jahuty's PHP client.

## Installation

This library requires [PHP 7.2+](https://secure.php.net).

It is multi-platform, and we strive to make it run equally well on Windows, Linux, and OSX.

It should be installed via [Composer](https://getcomposer.org). To do so, add the following line to the `require` section of your `composer.json` file (where `x` is the latest major version), and run `composer update`:

```javascript
{
   "require": {
       "jahuty/snippets-php": "^x"
   }
}
```

## Usage

Use the `key()` method to set your API key (ideally, once during startup):

```php
Snippet::key('123abc456def789ghi');
```

Then, use the `get()` method to retrieve a snippet (cast the return value to a `string`):

```html
<!doctype html>
<html>
<head>
    <title>My awesome example</title>
</head>
<body>
    <?php echo Snippet::get(123); ?>
</body>
```

## License

This library is licensed under the [MIT license](LICENSE).

## Compliance

This library strives to adhere to the following standards:

1. [Keep a Changelog 1.0](http://keepachangelog.com/en/1.0.0/)
2. [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
5. [Semantic Versioning 2.0](http://semver.org/spec/v2.0.0.html)

If you spot an error, please let us know!
