# Release Notes for v3.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 3.3.4

Released: 2019-01-29

### Added

* Added `Laravie\Codex\Response::then()` helper.

## 3.3.3

Released: 2018-12-30

### Changes

* Update `Laravie\Codex\Testing\Faker` to support handling upcoming version of `Http\Client\Common\HttpMethodsClient` which would be marked as `final`.
* Move reusable `Laravie\Codex\Request` methods to `Laravie\Codex\Support\Responsable` and `Laravie\Codex\Support\Versioning` trait.

## 3.3.2

Released: 2018-12-15

### Removed

* Remove interface methods that doesn't need to belongs to Codex.

## 3.3.1

Released: 2018-12-09

### Changes

* `Laravie\Codex\Concerns\Request\Multipart::stream()` updated to utilized `Laravie\Codex\Request::interactsWithResponse()` helper.

## 3.3.0

Released: 2018-12-04

### Added

* Add `Laravie\Codex\Response::isNotFound()` and `Laravie\Codex\Response::abortIfRequestNotFound()` methods.
* Add `Laravie\Codex\Exceptions\NotFoundException` exception.
* Add `Laravie\Codex\Exceptions\UnauthorizedException` exception.

### Deprecates

* Deprecate `Laravie\Codex\Exceptions\UnauthorizedHttpException`. Use `Laravie\Codex\Exceptions\UnauthorizedException` instead.

## 3.2.0

Released: 2018-11-07

### Added

* Add `Laravie\Codex\Request::proxyRequestViaVersion()` method to allow proxy request between API version for the same resource.

## 3.1.0

Released: 2018-08-29

### Added

* Add `Laravie\Codex\Payload` class to allow `body` content to be customized.
* Add `Laravie\Codex\Request::$validateResponseAutomatically` property to allow disabling response validation (default to `true`).

## 3.0.1

Released: 2018-04-18

### Added

* Add `Laravie\Codex\Response::isSuccessful()` and `Laravie\Codex\Response::isUnauthorized()` helper.

### Changes

* Allows to send either `Array` or `JSON` string as `$body` when using `Laravie\Codex\Testing\Faker::sendJson()` method.

## 3.0.0

Released: 2018-04-09

### Added

* Add new `Laravie\Codex\Concerns\Passport` to add configuration related to OAuth2 authentication.
* Add new `Laravie\Codex\Concerns\Request\Json` to handle sending request with `Content-Type` as `application/json`.
* Add new `Laravie\Codex\Support\HttpClient::stream()` to simplify sending stream data.
* Add new `Laravie\Codex\Testing\Faker` for testing helper, replacing deprecated `Laravie\Codex\Testing\FakeRequest`.
* Add new `Laravie\Codex\Concerns\Request\Multipart::stream()` as a helper to send `Content-Type` as `multipart/form-data`.
* Add `Laravie\Codex\Response::abortIfRequestHasFailed()` which throws `Laravie\Codex\Exceptions\HttpException` when status code between `400` to `599`.

### Changes

* `Laravie\Codex\Client::via()` now assign self to request object, `Laravie\Codex\Request` no longer assign `Client` from constructor.
* `$path` for `Laravie\Codex\Support\HttpClient::send()` now expect to be an instance of `Laravie\Codex\Contracts\Endpoint`, this simplify the URL generation from `Request` to `Client`.
* Optimize URL parsing when constructing `Laravie\Codex\Endpoint`.
* `Laravie\Codex\Response` now automatically validate unauthorized requests (status code with either `401` or `403`) from `validate()` method.
* Remove return typehint `self` for methods which is not marked as `final`.
* Any forward call to `Psr\Http\Message\UriInterface` on method that is prefix with `with` and return instance of `Psr\Http\Message\UriInterface` from `Laravie\Codex\Endpoint` will update `$this->uri` with the return value.

### Deprecates

* Deprecate `Laravie\Codex\Support\MultipartRequest`, use `Laravie\Codex\Concerns\Request\Multipart` instead.
* Deprecate `Laravie\Codex\Testing\FakeRequest`, use `Laravie\Codex\Testing\Faker` instead.

### Removed

* Remove `Laravie\Codex\Support\HttpClient::convertUriToEndpoint()` and `Laravie\Codex\Request::resolveUri()`, any URL manipulation such as adding `Psr\Http\Message\UriInterface::withUserInfo()` should be done within `Laravie\Codex\Request::getApiEndpoint()`.
* Remove deprecated `Laravie\Codex\Support\MakeHttpClient`, should directly use `Laravie\Codex\Discovery`.
* Remove deprecated `Laravie\Codex\Support\Resources`.
