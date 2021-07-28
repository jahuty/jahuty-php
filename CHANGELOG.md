# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- A `location` option to the `render` method, which allows you to report the absolute URL where the snippet is being rendered.
- The `prefer_latest` configuration option, a similarly intentional but shorter version of the `prefer_latest_content` configuration option.

### Changed

- Deprecated `prefer_latest_content` configuration option in favor `prefer_latest`.

## 5.3.0 - 2020-04-27

- Add the `prefer_latest_content` configuration setting to the client and render methods. This setting allows you to render a snippet's _latest_ content instead of the default _published_ content.

## 5.2.1 - 2020-03-16

- Add system tests for collections, parameterized snippets, and problems.

## 5.2.0 - 2020-03-05

- Add support for rendering and caching a collection of snippets via the `snippets->allRenders()` method.
- Remove the `Cache\Manager` and `Service\Factory` and move caching to the `Service\Snippet`. We got too fancy, and it got in the way.

## 5.1.1 - 2020-12-27

- Move `Jahuty\Ttl\Ttl` to `Jahuty\Cache\Ttl`.
- Change the caching section introduction and examples in the `README` to improve explanations.

## 5.1.0 - 2020-12-26

- Add a second, optional parameter to `Jahuty\Client`, an options array with support for the following options: `cache`, a `CacheInterface` implementation); `ttl`, a `null`, `int`, or `DateInterval` SDK-wide time-to-live; and, `base_uri`, the base URI for API requests (useful for testing).
- Add `fetch()` to `Jahuty\Client`, which checks the cache before requesting an action.
- Add `Jahuty\Cache\Memory`, a PSR-16 compliant in-memory cache adapter.
- Change `Jahuty\Client` to default to the in-memory cache, if no `cache` is configured.
- Move system tests to a separate test case, and improve the testing of `Jahuty\Client`.

## 5.0.0 - 2020-12-23

- Reduce root namespace from `Jahuty\Jahuty` to `Jahuty`.
- Change from a static-based architecture (e.g., `\Jahuty\Jahuty\Snippet::render(1)`) to an instance-based one (e.g., `$jahuty->snippets->render(1)`) to make the library easier to develop, test, and use.
- Fix links in `README`.
- Add code coverage analysis.

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
