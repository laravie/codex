# Changelog

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 1.0.1

Released: 2018-08-31

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
