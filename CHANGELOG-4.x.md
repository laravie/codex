# Release Notes for v4.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 4.1.0

Released: 2019-03-16

### Changes

* Add support for PSR-18 via `php-http/httplug` v2.0+.
* Update recommended PHPUnit to `^7.5 || ^8.0`.

## 4.0.5

Released: 2019-03-15

### Changes

* Suggest `phpunit/phpunit=^7.5`.

## 4.0.4

Released: 2019-02-26

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

## 4.0.3

Released: 2019-01-29

### Added

* Added `Laravie\Codex\Response::then()` helper.

## 4.0.2

Released: 2018-12-30

### Changes

* Update `Laravie\Codex\Testing\Faker` to support handling upcoming version of `Http\Client\Common\HttpMethodsClient` which would be marked as `final`.

## 4.0.1

Released: 2018-12-15

### Changes

* Move reusable `Laravie\Codex\Request` methods to `Laravie\Codex\Support\Responsable` and `Laravie\Codex\Support\Versioning` trait.

### Removed

* Remove interface methods that doesn't need to belongs to Codex.

## 4.0.0

Released: 2018-12-13

### Changes

* Moved `Laravie\Codex\Client::responseWith()` to `Laravie\Codex\Request::responseWith()`. If you are overriding the `Response` implementation please update your code.
* `Laravie\Codex\Support\HttpClient::send()` and `Laravie\Codex\Support\HttpClient::stream()` now return instance of `Psr\Http\Message\ResponseInterface`.
* Rename `Laravie\Codex\Response::$original` to `Laravie\Codex\Response::$message`.

### Removed

* Remove deprecated `Laravie\Codex\Testing\FakeRequest`, use `Laravie\Codex\Testing\Faker` instead.
* Remove deprecated `Laravie\Codex\Support\MultipartRequest`, use `Laravie\Codex\Concerns\Request\Multipart`.
