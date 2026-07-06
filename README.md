# SimpleCache

> A lightweight, extensible PHP caching library with support for **Array**, **File**, and **APCu** drivers, automatic driver resolution, cache statistics, and a clean developer-friendly API.

---

## Features

* 🚀 Lightweight and fast
* ✅ Strict Types (`declare(strict_types=1)`)
* ✅ PSR-12 compliant
* ✅ Array Driver
* ✅ File Driver
* ✅ APCu Driver
* ✅ Automatic Driver Resolution
* ✅ Cache Statistics
* ✅ Atomic Increment/Decrement (APCu)
* ✅ Secure File Serialization
* ✅ PHPUnit Tested
* ✅ PHPStan Level 8 Compatible
* ✅ Composer Ready

---

## Requirements

* PHP 8.2 or higher
* Composer

Optional:

* APCu extension (for APCu driver)

---

## Installation

```bash
composer require simple-cache/simple-cache
```

---

## Configuration

Create a configuration file and load it at runtime.

```php
<?php

return [
    'driver' => 'file',
    'path' => __DIR__ . '/cache',
    'ttl' => 3600,
    'debug' => false,
];
```

Then load the configuration into SimpleCache:

```php
use SimpleCache\Cache;

$config = require __DIR__ . '/cache.php';
Cache::configure($config);
```

You can also configure values directly:

```php
Cache::configure([
    'driver' => 'file',
    'path' => __DIR__ . '/cache',
]);
```

Supported driver values:

* `array`
* `file`
* `apcu`

You can also use `Cache::driverName()` to inspect the active driver:

```php
use SimpleCache\Cache;

echo Cache::driver()->value;
```

> Note: The `apcu` driver requires the APCu PHP extension to be installed and enabled.
> Note: The driver configuration is internally stored as a CacheDriver enum. String values such as 'apcu', 'file', and 'array' are automatically converted during configuration.

---

## Configuration API

Update multiple configuration values:

```php
Cache::configure([
    'driver' => 'apcu',
    'ttl' => 600,
]);
```

Update a single configuration value:

```php
Cache::setConfig('ttl', 600);
```

Retrieve a configuration value:

```php
Cache::getConfig('ttl');
```

Retrieve all configuration values:

```php
print_r(Cache::getConfig());
```

Reset configuration to defaults:

```php
Cache::resetConfig();
```
---

# Examples

Run the example scripts in the `examples/` folder:

```bash
php examples/basic_usage.php
php examples/file_driver_example.php
php examples/apcu_example.php
php examples/facade_methods.php
```

---

# Quick Start

```php
use SimpleCache\Cache;

Cache::put('username', 'John', 300);

echo Cache::get('username');
```

Output

```
John
```

---

# Selecting a Driver

```php
use SimpleCache\Cache;

Cache::configure([
    'driver' => 'array',
]);

Cache::configure([
    'driver' => 'file',
    'path' => __DIR__ . '/cache',
]);

Cache::configure([
    'driver' => 'apcu',
]);
```

Switch the active driver at runtime:

```php
use SimpleCache\Enums\CacheDriver;

Cache::driver(CacheDriver::FILE);

echo Cache::driver()->value;
```

If no driver is specified, SimpleCache automatically resolves the best available driver.

Priority:

```
APCu

↓

File

↓

Array
```

---

# Usage

## Store a Value

```php
Cache::put('name', 'John', 300);
```

---

## Retrieve a Value

```php
$name = Cache::get('name');
```

Default value:

```php
$name = Cache::get('name', 'Guest');
```

---

## Check if a Key Exists

```php
Cache::has('name');
```

---

## Delete a Key

```php
Cache::forget('name');
```

---

## Clear the Cache

```php
Cache::clear();
```

---

## Remember

```php
$user = Cache::remember(
    'user_1',
    3600,
    fn () => fetchUserFromDatabase()
);
```

The callback only executes if the key is missing.

---

## Store Forever

```php
Cache::forever(
    'site_name',
    'SimpleCache'
);
```

---

## Add Only If Missing

```php
Cache::add(
    'visits',
    1
);
```

Returns:

* `true` if stored
* `false` if the key already exists

---

## Pull

Retrieve and remove a value.

```php
$value = Cache::pull('token');
```

---

## Store Multiple Values

```php
Cache::putMany([

    'name' => 'John',
    'age' => 25,

], 600);
```

---

## Retrieve Multiple Values

```php
$data = Cache::many([
    'name',
    'age',
]);
```

Returns

```php
[
    'name' => 'John',
    'age' => 25,
]
```

---

## Increment

```php
Cache::increment('counter');
```

Increment by custom amount.

```php
Cache::increment(
    'counter',
    5
);
```

---

## Decrement

```php
Cache::decrement('counter');

Cache::decrement(
    'counter',
    3
);
```

## Flush the Active Driver

```php
Cache::flush();
```
Flushes all cached values from the active cache driver.

---

# Cache Statistics

Retrieve cache usage statistics.

```php
print_r(Cache::stats());
```

Example output

```php
Array
(
    [hits] => 15
    [misses] => 3
    [total] => 18
    [hit_rate] => 83.33
)
```

---

# Cache Information

```php
print_r(Cache::info());
```

Example output

```php
Array
(
    [driver] => file

    [driver_class] => SimpleCache\Drivers\FileDriver

    [statistics] => Array
        (
            [hits] => 15
            [misses] => 3
            [total] => 18
            [hit_rate] => 83.33
        )

    [php] => 8.5.0

    [apcu] => true
)
```

---

# Available Drivers

| Driver | Description                             |
| ------ | --------------------------------------- |
| Array  | In-memory cache (request lifetime only) |
| File   | Persistent filesystem cache             |
| APCu   | High-performance shared memory cache    |

---

# Driver Comparison

| Feature                       |  Array        |        File         |    APCu      |
| -------------------------     | :-----:       | :----------------:  | :--------:   |
| Shared Between Requests       |    ❌         |          ✅         |      ✅      |
| Survives Server Restart       |    ❌         |          ✅         |      ❌      |
| TTL Support                   |    ✅         |          ✅         |      ✅      |
| Atomic Increment              |    ❌         |          ❌         |      ✅      |
| Fastest                       |  ⭐⭐⭐⭐⭐ |         ⭐⭐        |    ⭐⭐⭐⭐ |
| Best For                      | Testing       | Small Applications    | Production    |

---

# API Reference

| Method        | Description               |
| ------------- | ------------------------- |
| `put()`       | Store a value             |
| `get()`       | Retrieve a value          |
| `has()`       | Determine if a key exists |
| `forget()`    | Delete a key              |
| `clear()`     | Clear cached values           |
| `flush()`     | Flush the active driver
| `remember()`  | Cache callback result     |
| `forever()`   | Store indefinitely        |
| `add()`       | Store only if missing     |
| `pull()`      | Retrieve and delete       |
| `many()`      | Retrieve multiple values  |
| `putMany()`   | Store multiple values     |
| `increment()` | Increment integer value   |
| `decrement()` | Decrement integer value   |
| `resetStats()`| Reset Cache Statistics    |
| `stats()`     | Cache statistics          |
| `info()`      | Driver information        |
| `driver()`    | Get the current driver name |
| `driverClass()`| Get the active driver class |
| `configure()` | Set multiple configuration values |
| `setConfig()` | Set a single configuration value |
| `getConfig()` | Read configuration values |
| `resetConfig()` | Reset configuration to defaults |

---

# Real World Example

```php
use SimpleCache\Cache;

$user = Cache::remember(
    'user_5000',
    3600,
    function () use ($pdo) {
        $stmt = $pdo->query(
            "SELECT * FROM users WHERE id = 5000"
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
);
```

The first request retrieves the user from the database and stores it in the cache. Subsequent requests return the cached value until the TTL expires.

---

# Testing

Run all quality checks.

```bash
composer check
```

Run PHPUnit.

```bash
composer test
```

Run PHPStan.

```bash
composer analyse
```

Run PHP CS Fixer.

```bash
composer fix
```

---

# Project Structure

```
src/
tests/
benchmarks/
docs/

composer.json
phpunit.xml
phpstan.neon
.php-cs-fixer.php
README.md
LICENSE
CHANGELOG.md
```

---

# Benchmarks

## Benchmarks

A benchmark application demonstrating the performance of each cache driver is included in the repository.

Typical comparisons include:

- Direct database access
- Array driver
- File driver
- APCu driver
- Cache hit vs cache miss

---

# Roadmap

## v0.2

* Redis Driver
* Memcached Driver
* Benchmark Suite
* Additional Driver Tests

## v0.3

* PSR-16 Compatibility
* Tagged Cache Support
* Cache Events

## v1.0

* Stable Public API
* Performance Optimizations
* Complete Documentation
* Long-term Support (LTS)

---

# Contributing

Contributions are welcome.

Before submitting a pull request:

```bash
composer install

composer check
```

Please ensure:

* All tests pass
* PHPStan reports no errors
* Code follows PSR-12

---

# License

This project is licensed under the MIT License.

---

# Changelog

See **CHANGELOG.md** for release history.

After this, I'd recommend creating these files in order:

1. `LICENSE` (MIT)
2. `CHANGELOG.md`
3. `CONTRIBUTING.md`
4. `SECURITY.md`
5. GitHub Actions workflow (`.github/workflows/tests.yml`)

Those five additions will make the project fully ready for GitHub and Packagist.
