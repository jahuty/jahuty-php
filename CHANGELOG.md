# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 5.0.0 - 2020-12-12

- Reduce root namespace from `Jahuty\Jahuty` to `Jahuty`.
- Remove `static` to make the library easier to develop, test, and use.
- Fix links in `README`.

## 4.0.0 - 2020-07-04

- Rename `Serivce\Get` to `Service\Render`.
- Rename `Snippet::get` to `Snippet::render`.
- Change the optional second argument of `render()` from an array of `params` to an options array with a `params` key.
- Rename `Data\Snippet` to `Data\Render`.
- Remove `id` attribute from `Data\Render`.
- Change API endpoint from `snippets/:id` to `snippets/:id/render`.

## 3.1.1 - 2020-03-15

- Change snippet parameters to JSON query string parameter (e.g., `params={"foo":"bar"}`) from serialized query string parameter (e.g.,`params[foo]=bar`).

## 3.1.0 - 2020-03-13

- Add snippet parameters.
- Add `Jahuty::getClient()` to memoize Guzzle's HTTP client.
- Remove `Request` object and move constants like `HEADERS` and `BASE_URI` to `Jahuty`.

## 3.0.0 - 2020-03-08

- Add `Jahuty\Jahuty\Jahuty` to store API key and current version number.
- Remove `Jahuty\Jahuty\Snippet::key()` method.

## 2.0.0 - 2020-03-08

- Change namespace from `Jahuty\Snippet` to `Jahuty\Jahuty`.
- Change repository and package name from `jahuty/snippets-php` to `jahuty/jahuty-php`.

## 1.0.0 - 2019-09-02

- Initial release
