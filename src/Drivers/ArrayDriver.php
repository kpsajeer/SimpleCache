<?php

declare(strict_types=1);

namespace SimpleCache\Drivers;

class ArrayDriver extends AbstractDriver
{
    /**
     * In-memory cache storage.
     *
     * @var array<string,array{value:mixed,expires:int}>
     */
    protected array $storage = [];

    protected function doGet(string $key): mixed
    {
        if (! $this->doHas($key)) {
            return self::$notFound;
        }

        return $this->storage[$key]['value'];
    }

    protected function doPut(string $key, mixed $value, int $ttl): bool
    {
        $this->storage[$key] = [
            'value'   => $value,
            'expires' => $ttl > 0 ? \time() + $ttl : 0,
        ];

        return true;
    }

    protected function doHas(string $key): bool
    {
        if (! isset($this->storage[$key])) {
            return false;
        }

        $expires = $this->storage[$key]['expires'];

        if ($expires !== 0 && \time() > $expires) {
            unset($this->storage[$key]);
            return false;
        }

        return true;
    }

    protected function doDelete(string $key): bool
    {
        unset($this->storage[$key]);

        return true;
    }

    protected function doClear(): bool
    {
        $this->storage = [];

        return true;
    }

    public function increment(string $key, int $value = 1): int
    {
        if (! isset($this->storage[$key])) {
            $this->storage[$key] = [
                'expires' => 0,
                'value' => 0,
            ];
        }

        if (! is_int($this->storage[$key]['value'])) {
            throw new \InvalidArgumentException(
                "Cache value for '{$key}' must be an integer."
            );
        }

        $this->storage[$key]['value'] += $value;

        return $this->storage[$key]['value'];
    }

    public function decrement(string $key, int $value = 1): int
    {
        return $this->increment($key, -$value);
    }
}
