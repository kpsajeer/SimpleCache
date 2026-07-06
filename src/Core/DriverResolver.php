<?php

declare (strict_types=1);

namespace SimpleCache\Core;

use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class DriverResolver
{
    public static function resolve(?CacheDriver $driver = null): CacheDriver
    {
        $driverConfig = Cache::getConfig('driver', CacheDriver::ARRAY->value);
        $driver ??= CacheDriver::tryFrom(
            $driverConfig instanceof CacheDriver
                ? $driverConfig->value
                : (string) $driverConfig
        ) ?? CacheDriver::ARRAY;

        if ($driver === CacheDriver::APCU) {
            return self::resolveApcu();
        }

        if ($driver === CacheDriver::FILE) {
            return self::resolveFile();
        }

        return CacheDriver::ARRAY;
    }

    protected static function resolveApcu(): CacheDriver
    {
        if (\extension_loaded('apcu')) {
            return CacheDriver::APCU;
        }

        return self::resolveFile();
    }

    protected static function resolveFile(): CacheDriver
    {
        $path = Cache::getConfig('path');

        if (!is_string($path) || trim($path) === '') {
            throw new \InvalidArgumentException(
                'File cache path is required.'
            );
        }

        if (\is_dir($path) || @\mkdir($path, 0777, true)) {
            return CacheDriver::FILE;
        }

        return CacheDriver::ARRAY;
    }
}
