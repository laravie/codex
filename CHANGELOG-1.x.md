# Release Notes for v1.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 1.6.2

Released: 2018-04-04

### Fixes

* Fixes `Laravie\Codex\Support\HttpClient::stream()` and `Laravie\Codex\Support\MultipartRequest::stream()` implementation.

## 1.6.1

Released: 2018-04-04

### Added

* Added `Laravie\Codex\Support\MultipartRequest::stream()` method.

### Changes

* Normalize given `$path` when constructing `Laravie\Codex\Endpoint`.
* `Laravie\Codex\Sanitizer` should be able to lazy load casts object.
* Improves tests and docblocks.
* Use `Laravie\Codex\Contracts\Endpoint` as typehint instead of `Laravie\Codex\Endpoint` whenever possible.

## 1.6.0

Released: 2018-04-03

### Changes

* Bump minimum PHP to 7.0+.
* `Laravie\Codex\Endpoint` now implements `Laravie\Codex\Contracts\Endpoint`.
* Improves tests.

## 1.5.2

Released: 2018-04-04

### Fixes

* Fixes `Laravie\Codex\Support\HttpClient::stream()` and `Laravie\Codex\Support\MultipartRequest::stream()` implementation.

## 1.5.1 

Released: 2018-04-04

### Added

* Added `Laravie\Codex\Support\MultipartRequest::stream()` method.

### Changes

* Normalize given `$path` when constructing `Laravie\Codex\Endpoint`.
* `Laravie\Codex\Sanitizer` should be able to lazy load casts object.
* Improves tests and docblocks.

## 1.5.0

Released: 2018-03-12

### Added

* Added `Laravie\Codex\Client::queries()` to get all executed requests and responses.

### Changes

* Bump minimum PHP to 5.6+.

## 1.4.1

Released: 2018-03-09

### Changes

* Able to get response content from instance of `Psr\Http\Message\StreamInterface`.

## 1.4.0

Released: 2018-02-04

### Added

* Added `Laravie\Codex\Client::via()` method.
* Added `Laravie\Codex\Client::uses()` method to replace deprecated `resource()`.

### Changes

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

### Deprecated

* Deprecate `Laravie\Codex\Client::resource()` method, please use `Laravie\Codex\Client::uses()` instead.

## 1.3.1

Released: 2017-12-28

### Changes

* Bump supported dependencies. 

## 1.3.0

Released: 2017-11-14

### Added

* Add `Laravie\Codex\Testing\FakeRequest`.

## 1.2.0

Released: 2017-10-31

### Added

* Add `Laravie\Codex\Response::validateWith()` helper to validate the response for SDK.

## 1.1.0

Released: 2017-09-07

### Changes

* `Laravie\Codex\Exceptions\HttpException` now pre-populate `$message` and `$code` from `$response` if both is not populated.
* Abstract http related request from `Laravie\Codex\Client` to `Laravie\Codex\Support\Request`.
* `Laravie\Codex\Endpoint` now cache and build `GuzzleHttp\Psr7\Uri` immediately.
* Move populating body to query string from `Laravie\Codex\Request` to `Laravie\Codex\Support\Request`.

## 1.0.1

Released: 2017-08-31

## Added

* Added `getUri()`, `getPath()` and `getQuery()` on `Laravie\Codex\Endpoint` class.

### Changes

* Allow `Laravie\Codex\Request::send()` to properly build URI if `$path` is an instance of `Laravie\Codex\Endpoint`.

## 1.0.0

Released: 2017-08-31

### New

* Initial stable release.

### Added

* Added following contracts:
    - `Laravie\Codex\Contracts\Cast`.
    - `Laravie\Codex\Contracts\Client`.
    - `Laravie\Codex\Contracts\Request`.
    - `Laravie\Codex\Contracts\Response`.
    - `Laravie\Codex\Contracts\Sanitizer`.
* Added `Laravie\Codex\Discovery::make()` helper method to build HTTP Client.
* Added `Laravie\Codex\Discovery::flush()` helper method to flush cached HTTP Client.
* Added `getBody()`, `getContent()` and `getStatusCode()` method to `Laravie\Codex\Response`, which is also part of `Response` contract.
* Added `sanitizeFrom()` and `sanitizeTo()` to `Laravie\Codex\Support\WithSanitizer` trait.

### Changes

* Move declaration `sanitizeWith()` from `Laravie\Codex\Client` to `Request` class, ignore this if sanitization isn't needed.
* `Laravie\Codex\Request` should set sanitizer to `Laravie\Codex\Response` before running `Response::validate()` method.
* Improves sanitization supports.
* Body shouldn't be converted to query string if it's an instance of `StreamInterface`.

### Removed

* Remove `makeHttpClient()` and `makeFreshHttpClient()` `Laravie\Codex\Client`, add `Laravie\Codex\Support\MakeHttpClient` trait if you need the functionality.
