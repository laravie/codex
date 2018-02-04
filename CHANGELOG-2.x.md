# Changelog

This changelog references the relevant changes (bug and security fixes) done to `laravie/codex`.

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
