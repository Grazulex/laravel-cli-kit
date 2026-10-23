# Changelog

All notable changes to this project will be documented in this file.

## [2.0.0](https://github.com/Grazulex/laravel-cli-kit/releases/tag/v2.0.0) (2026-10-23)

### ⚠ BREAKING CHANGES

- **Laravel 13**: requires Laravel 13.x, Laravel 12 is no longer supported (#6)

### Changed

- Update `laravel/framework` to ^13.0 and `laravel/tinker` to ^3.0 (#6)
- Update `symfony/*` components (clock, css-selector, event-dispatcher, string, translation) to ^8.0 (#6)
- Update `pestphp/pest` and `pestphp/pest-plugin-laravel` to ^5.0 (#6)

## [1.1.1](https://github.com/Grazulex/laravel-cli-kit/releases/tag/v1.1.1) (2026-10-23)

### Security

- Update all Composer dependencies within existing constraints: `composer audit` now reports no advisories (was 41: laravel/framework, guzzlehttp/guzzle, league/commonmark, symfony/*…) (#4)

## [1.1.0](https://github.com/Grazulex/laravel-cli-kit/releases/tag/v1.1.0) (2026-10-08)

### Changed

- **Minimum PHP version is now 8.4**: PHP 8.3 is no longer supported (#2)
- CI test matrix now runs PHP 8.4 and 8.5 (#2)
- Docker image now based on PHP 8.4 (#2)

## [1.0.0](https://github.com/Grazulex/laravel-cli-kit/releases/tag/v1.0.0) (2025-12-25)

### Features

- Initial Laravel CLI Kit ([99da10c](https://github.com/Grazulex/laravel-cli-kit/commit/99da10c47ba895dd345027fe3d5d69cf55e956e2))
