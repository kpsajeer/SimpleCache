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

Create a configuration file.

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | Supported:
    | array
    | file
    | apcu
    |
    */

    'driver' => 'file',

    /*
    |--------------------------------------------------------------------------
    | File Cache Path
    |--------------------------------------------------------------------------
    */

    'path' => __DIR__ . '/cache',

];
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
use SimpleCache\Enums\CacheDriver;

Cache::driver(CacheDriver::ARRAY);

Cache::driver(CacheDriver::FILE);

Cache::driver(CacheDriver::APCU);
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
    [driver] => SimpleCache\Drivers\FileDriver

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

| Feature          |  Array  |        File        |    APCu    |
| ---------------- | :-----: | :----------------: | :--------: |
| Persistent       |    ❌    |          ✅         |      ❌     |
| TTL Support      |    ✅    |          ✅         |      ✅     |
| Atomic Increment |    ❌    |          ❌         |      ✅     |
| Fastest          |  ⭐⭐⭐⭐⭐  |         ⭐⭐         |    ⭐⭐⭐⭐    |
| Best For         | Testing | Small Applications | Production |

---

# API Reference

| Method        | Description               |
| ------------- | ------------------------- |
| `put()`       | Store a value             |
| `get()`       | Retrieve a value          |
| `has()`       | Determine if a key exists |
| `forget()`    | Delete a key              |
| `clear()`     | Flush the cache           |
| `remember()`  | Cache callback result     |
| `forever()`   | Store indefinitely        |
| `add()`       | Store only if missing     |
| `pull()`      | Retrieve and delete       |
| `many()`      | Retrieve multiple values  |
| `putMany()`   | Store multiple values     |
| `increment()` | Increment integer value   |
| `decrement()` | Decrement integer value   |
| `stats()`     | Cache statistics          |
| `info()`      | Driver information        |
| `driver()`    | Switch cache driver       |

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

Benchmark support will be added in a future release.

Planned comparisons:

* No Cache
* Array Driver
* File Driver
* APCu Driver

---

# Roadmap

## v0.2

* Redis Driver
* Memcached Driver
* Benchmark Suite

## v0.3

* PSR-16 Compatibility
* Tagged Cache Support

## v1.0

* Stable Public API
* Performance Improvements
* Extended Documentation

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
