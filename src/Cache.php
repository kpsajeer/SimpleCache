<?php

declare(strict_types=1);

namespace SimpleCache;

use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Core\CacheFactory;
use SimpleCache\Enums\CacheDriver;
use SimpleCache\Support\Statistics;

class Cache
{
    /**
     * Current cache driver
     */
    protected static ?CacheDriverInterface $driver = null;

    /**
     * Get the active driver
     */
    protected static function getDriver(): CacheDriverInterface
    {
        if (self::$driver === null) {
            self::$driver = CacheFactory::create();
        }

        return self::$driver;
    }

    /**
     * Change driver at runtime
     */
    public static function setDriver(CacheDriver $driver): void
    {
        self::$driver = CacheFactory::create($driver);
    }

    /**
     * Get the name of the current driver
     */
    public static function driverName(): string
    {
        return self::getDriver()::class;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        return self::getDriver()->$method(...$arguments);
    }

    /**
     * @return array{
     *     hits:int,
     *     misses:int
     * }
     */
    public static function stats(): array
    {
        return Statistics::all();
    }

    public static function resetStats(): void
    {
        Statistics::reset();
    }

    /**
     * @return array{
     *     driver:string,
     *     statistics:array{
     *         hits:int,
     *         misses:int
     *     },
     *     php:string,
     *     apcu:bool
     * }
     */
    public static function info(): array
    {
        return [
            'driver'     => self::getDriver()::class,
            'statistics' => Statistics::all(),
            'php'        => PHP_VERSION,
            'apcu'       => extension_loaded('apcu'),
        ];
    }
}
