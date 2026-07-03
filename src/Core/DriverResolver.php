<?php

declare (strict_types=1);

namespace SimpleCache\Core;

use SimpleCache\Config\Config;
use SimpleCache\Enums\CacheDriver;

class DriverResolver
{
    public static function resolve(?CacheDriver $driver = null): CacheDriver
    {
        // $driver ??= Config::get('driver', CacheDriver::ARRAY);
        $driver ??= CacheDriver::tryFrom(
            Config::get('driver', CacheDriver::ARRAY->value)
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
        $path = Config::get('path');

        if (\is_dir($path) || @\mkdir($path, 0777, true)) {
            return CacheDriver::FILE;
        }

        return CacheDriver::ARRAY;
    }
}
