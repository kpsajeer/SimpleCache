<?php

declare(strict_types=1);

namespace SimpleCache\Drivers;

use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Support\Statistics;

abstract class AbstractDriver implements CacheDriverInterface
{
    protected static ?object $notFound;

    public function __construct()
    {
        self::$notFound ??= new \stdClass();
    }

    protected function validateKey(string $key): void
    {
        if ($key === '') {
            throw new \InvalidArgumentException('Cache key must not be empty.');
        }
    }

    abstract protected function doGet(string $key): mixed;

    abstract protected function doPut(string $key, mixed $value, int $ttl): bool;

    abstract protected function doDelete(string $key): bool;

    abstract protected function doClear(): bool;

    abstract protected function doHas(string $key): bool;

    public function get(string $key, mixed $default = null): mixed
    {
        $this->validateKey($key);

        $value = $this->doGet($key);
        if ($value === self::$notFound) {
            Statistics::miss();
            return $default;
        }
        Statistics::hit();
        return $value;
    }

    public function put(string $key, mixed $value, int $ttl = 0): bool
    {
        $this->validateKey($key);

        return $this->doPut($key, $value, $ttl);
    }

    public function has(string $key): bool
    {
        $this->validateKey($key);

        return $this->doHas($key);
    }

    public function forget(string $key): bool
    {
        $this->validateKey($key);

        return $this->doDelete($key);
    }

    public function clear(): bool
    {
        return $this->doClear();
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $this->validateKey($key);

        $value = $this->get($key, self::$notFound);

        if ($value !== self::$notFound) {
            return $value;
        }

        $value = $callback();
        if (! $this->put($key, $value, $ttl)) {
            throw new \RuntimeException("Failed to store value for key '{$key}' in cache.");
        }
        return $value;
    }

    public function forever(string $key, mixed $value): bool
    {
        $this->validateKey($key);

        return $this->put($key, $value, 0);
    }

    public function add(string $key, mixed $value, int $ttl = 0): bool
    {
        $this->validateKey($key);

        if ($this->has($key)) {
            return false;
        }

        return $this->put($key, $value, $ttl);
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        $this->validateKey($key);

        $value = $this->get($key, self::$notFound);

        if ($value === self::$notFound) {
            return $default;
        }

        $this->forget($key);
        return $value;
    }

    /**
     * Retrieve multiple cache items.
     *
     * @param string[] $keys
     * @return array<string, mixed>
     */
    public function many(array $keys, mixed $default = null): array
    {
        $results = [];

        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
        }

        return $results;
    }

    /**
     * Store multiple cache items.
     *
     * @param array<string, mixed> $values
     */
    public function putMany(array $values, int $ttl = 0): bool
    {
        foreach ($values as $key => $value) {
            if (! $this->put($key, $value, $ttl)) {
                return false;
            }
        }

        return true;
    }

    abstract public function increment(string $key, int $value = 1): int;

    abstract public function decrement(string $key, int $value = 1): int;
}
