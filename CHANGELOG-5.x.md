# Release Notes for v5.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

## 5.3.1

Released: 2020-12-28

### Fixes

* Fixes method declaration on `Laravie\Codex\Common\HttpClient::stream()`.
* Fixes `Laravie\Codex\Contracts\Client::stream()` contract.

## 5.3.0

Released: 2020-12-28

### Changes

* Add support for PHP 8.

## 5.2.0

Released: 2020-02-05

### Changes 

* Bump minimum PHP to 7.2+.
* Add support for PHPUnit 9.

## 5.1.1

Released: 2019-11-03

### Fixes

* Avoid trying to rebuild `Psr\Http\Message\StreamInterface` from `$body` when using `Laravie\Codex\Concerns\Request\Multipart::stream()`.

## 5.1.0

Released: 2019-10-11

### Changes

* Update to `laravie/codex-common` to `v1.2.0` and above.

## 5.0.1

Released: 2019-05-30

### Changes

* Update to `laravie/codex-common` to `v1.0.1` and above.

## 5.0.0

Released: 2019-03-29

### Changes

* Moved Common Component to `laravie/codex-common`.
* Moved Filtering Component to `laravie/codex-filter`.
