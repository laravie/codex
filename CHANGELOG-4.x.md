# Release Notes for v4.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## Unreleased

### Changes

* Moved `Laravie\Codex\Client::responseWith()` to `Laravie\Codex\Request::responseWith()`. If you are overriding the `Response` implementation please update your code.
* `Laravie\Codex\Support\HttpClient::send()` and `Laravie\Codex\Support\HttpClient::stream()` now return instance of `Psr\Http\Message\ResponseInterface`.
* Rename `Laravie\Codex\Response::$original` to `Laravie\Codex\Response::$message`.

### Removed

* Remove deprecated `Laravie\Codex\Testing\FakeRequest`, use `Laravie\Codex\Testing\Faker` instead.
* Remove deprecated `Laravie\Codex\Support\MultipartRequest`, use `Laravie\Codex\Concerns\Request\Multipart`.
