<?php

declare(strict_types=1);

namespace SimpleCache\Drivers;

use InvalidArgumentException;
use RuntimeException;
use SimpleCache\Support\CachePayload;
use SimpleCache\Support\Serializer;

class FileDriver extends AbstractDriver
{
    protected string $path;

    public function __construct(string $path)
    {
        parent::__construct();
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

        if ($this->path === '') {
            throw new InvalidArgumentException(
                'Cache directory cannot be empty.'
            );
        }

        if (! is_dir($this->path) && ! @mkdir($this->path, 0777, true)) {
            throw new RuntimeException(
                "Unable to create cache directory: {$this->path}"
            );
        }
    }

    protected function filename(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . hash('sha256', $key) . '.cache';
    }

    private function loadPayload(string $key): ?CachePayload
    {
        $file = $this->filename($key);

        if (! file_exists($file)) {
            return null;
        }

        $contents = file_get_contents($file);

        if ($contents === false) {
            return null;
        }

        $payload = Serializer::decode($contents);

        if ($payload === null || $payload->expired()) {
            @unlink($file);
            return null;
        }

        return $payload;
    }

    protected function doPut(string $key, mixed $value, int $ttl): bool
    {
        $payload = CachePayload::create($value, $ttl);

        return file_put_contents(
            $this->filename($key),
            Serializer::encode($payload),
            LOCK_EX
        ) !== false;
    }

    protected function doGet(string $key): mixed
    {
        $payload = $this->loadPayload($key);

        return $payload ? $payload->value : self::$notFound;
    }

    protected function doHas(string $key): bool
    {
        return $this->doGet($key) !== self::$notFound;
    }

    protected function doDelete(string $key): bool
    {
        $file = $this->filename($key);
        return ! file_exists($file) || unlink($file);
    }

    protected function doClear(): bool
    {
        $result = true;
        $files = glob($this->path . DIRECTORY_SEPARATOR . '*.cache');
        if ($files === false) {
            return true;
        }

        foreach ($files as $file) {
            if (! @unlink($file)) {
                $result = false;
            }
        }
        return $result;
    }

    public function increment(string $key, int $value = 1): int
    {
        $payload = $this->loadPayload($key);

        if ($payload === null) {
            $payload = CachePayload::create(0, 0);
        }

        if (! is_int($payload->value)) {
            throw new \InvalidArgumentException(
                "The value for key '{$key}' is not an integer and cannot be incremented."
            );
        }

        $payload->value += $value;

        if (
            file_put_contents(
                $this->filename($key),
                Serializer::encode($payload),
                LOCK_EX
            ) === false
        ) {
            throw new \RuntimeException(
                "Failed to update cache key '{$key}'."
            );
        }

        return $payload->value;
    }

    public function decrement(string $key, int $value = 1): int
    {
        return $this->increment($key, -$value);
    }
}
