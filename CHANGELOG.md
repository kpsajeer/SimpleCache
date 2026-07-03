# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

## [0.1.0] - 2026-07-03

### Added

- Cache facade
- Array cache driver
- File cache driver
- APCu cache driver
- Automatic driver resolution
- CacheFactory
- DriverResolver
- Statistics support
- Secure file serialization
- Increment and decrement support
- Multiple item operations (`many`, `putMany`)
- PHPUnit test suite
- PHPStan Level 8 support
- PHP CS Fixer configuration
- README documentation

### Security

- Safe unserialize using `allowed_classes => false`
- Validation of serialized payloads before decoding

---

## [Unreleased]

### Planned

- Redis Driver
- Memcached Driver
- Benchmark Suite
- PSR-16 Compatibility
- GitHub Actions CI