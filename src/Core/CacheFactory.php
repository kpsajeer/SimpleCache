<?php

declare(strict_types=1);

namespace SimpleCache\Core;

use SimpleCache\Cache;
use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Drivers\ApcuDriver;
use SimpleCache\Drivers\ArrayDriver;
use SimpleCache\Drivers\FileDriver;
use SimpleCache\Enums\CacheDriver;

final class CacheFactory
{
    /**
     * Create a cache driver.
     *
     * @param array<string, mixed> $config
     */
    public static function create(?CacheDriver $driver = null, array $config = []): CacheDriverInterface
    {
        if ($driver === null && isset($config['driver'])) {
            $driver = $config['driver'] instanceof CacheDriver
                ? $config['driver']
                : CacheDriver::tryFrom((string) $config['driver']);
        }

        $driver ??= DriverResolver::resolve();

        return match ($driver) {
            CacheDriver::ARRAY => new ArrayDriver(),
            CacheDriver::APCU => new ApcuDriver(),
            CacheDriver::FILE => new FileDriver(
                self::resolvePath($config)
            )
        };
    }

    /**
     * Resolve the file cache path.
     *
     * @param array<string, mixed> $config
     */
    private static function resolvePath(array $config): string
    {
        $path = $config['path'] ?? Cache::getConfig('path');
        if (!is_string($path) || trim($path) === '') {
            throw new \InvalidArgumentException(
                'File cache path is required.'
            );
        }

        return $path;
    }
}
