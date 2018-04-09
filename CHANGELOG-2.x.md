# Changelog for v2.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 2.2.2

Released: 2018-04-04

### Fixes

* Fixes `Laravie\Codex\Support\HttpClient::stream()` and `Laravie\Codex\Support\MultipartRequest::stream()` implementation.

## 2.2.1

Released: 2018-04-04

### Added

* Added `Laravie\Codex\Support\MultipartRequest::stream()` method.

### Changes

* Normalize given `$path` when constructing `Laravie\Codex\Endpoint`.
* `Laravie\Codex\Sanitizer` should be able to lazy load casts object.
* Improves tests and docblocks.
* Use `Laravie\Codex\Contracts\Endpoint` as typehint instead of `Laravie\Codex\Endpoint` whenever possible.
* Mark `Laravie\Codex\Support\HttpClient::convertUriToEndpoint()` as `final`.

## 2.2.0

Released: 2018-03-14

### Added

* Added `Laravie\Codex\Client::queries()` to get all executed requests and responses.

### Changes

* Simplify typehint for `Laravie\Codex\Testing\FakeRequest`.

## 2.1.1

Released: 2018-03-09

### Changes

* Able to get response content from instance of `Psr\Http\Message\StreamInterface`.

## 2.1.0

Released: 2018-02-04

### Added

* Added `Laravie\Codex\Client::via()` method.
* Added `Laravie\Codex\Client::uses()` method to replace deprecated `resource()`.

### Deprecated

* Deprecate `Laravie\Codex\Client::resource()` method, please use `Laravie\Codex\Client::uses()` instead.

## 2.0.0

Released: 2018-01-22

### Changes

* Update minimum PHP version to 7.1+.
* Mark `Laravie\Codex\Discovery` class as `final`.
* Updates `Laravie\Codex\Support\MultipartRequest` helper methods as `final`.
* Updates `Laravie\Codex\Support\Resources` helper methods as `final`.
* Updates `Laravie\Codex\Support\WithSanitizer` helper methods as `final`.
* Set `final` to `createFromUri()` method in `Laravie\Codex\Endpoint`.
* Set `final` to `validateWith()` method in `Laravie\Codex\Response`.
* Set `final` to following methods in `Laravie\Codex\Request`:
    - `getVersion()`
    - `mergeApiHeaders()`
    - `mergeApiBody()`
