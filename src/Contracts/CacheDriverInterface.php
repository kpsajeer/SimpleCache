<?php

declare(strict_types=1);

namespace SimpleCache\Contracts;

interface CacheDriverInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function put(string $key, mixed $value, int $ttl = 0): bool;

    public function has(string $key): bool;

    public function forget(string $key): bool;

    public function clear(): bool;

    public function remember(string $key, int $ttl, callable $callback): mixed;

    public function forever(string $key, mixed $value): bool;

    public function add(string $key, mixed $value, int $ttl = 0): bool;

    public function pull(string $key, mixed $default = null): mixed;

    /**
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function many(array $keys): array;

    /**
     * @param array<string, mixed> $values
     */
    public function putMany(array $values, int $ttl = 0): bool;

    public function increment(string $key, int $value = 1): int;

    public function decrement(string $key, int $value = 1): int;
}
