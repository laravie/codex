# Release Notes for v3.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

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
