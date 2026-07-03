<?php

declare(strict_types=1);

namespace SimpleCache\Drivers;

class ApcuDriver extends AbstractDriver
{
    private function initializeCounter(string $key): void
    {
        if (!\apcu_exists($key) && !\apcu_add($key, 0)) {
            throw new \RuntimeException(
                "Failed to initialize APCu counter '{$key}'."
            );
        }
    }

    protected function doGet(string $key): mixed
    {
        $success = false;
        $value = \apcu_fetch($key, $success);
        return $success ? $value : self::$notFound;
    }

    protected function doPut(string $key, mixed $value, int $ttl): bool
    {
        return \apcu_store($key, $value, $ttl);
    }

    protected function doHas(string $key): bool
    {
        return \apcu_exists($key);
    }

    protected function doDelete(string $key): bool
    {
        return !\apcu_exists($key) || \apcu_delete($key);
    }

    protected function doClear(): bool
    {
        return \apcu_clear_cache();
    }

    public function increment(string $key, int $value = 1): int
    {
        self::validateKey($key);
        $this->initializeCounter($key);

        $success  = false;
        $newValue = \apcu_inc($key, $value, $success);

        if (! $success || $newValue === false) {
            throw new \InvalidArgumentException(
                "The value for key '{$key}' is not an integer and cannot be incremented."
            );
        }

        return $newValue;
    }

    public function decrement(string $key, int $value = 1): int
    {
        self::validateKey($key);
        $this->initializeCounter($key);

        $success  = false;
        $newValue = \apcu_dec($key, $value, $success);

        if (! $success || $newValue === false) {
            throw new \InvalidArgumentException(
                "The value for key '{$key}' is not an integer and cannot be decremented."
            );
        }

        return $newValue;
    }
}
