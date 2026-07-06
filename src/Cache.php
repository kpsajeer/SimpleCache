<?php

declare (strict_types=1);

namespace SimpleCache;

use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Core\CacheFactory;
use SimpleCache\Enums\CacheDriver;
use SimpleCache\Support\Statistics;

class Cache
{
    private const DEFAULT_CONFIG = [
        'driver' => CacheDriver::ARRAY,
        'ttl' => 3600,
        'debug' => false,
    ];
    /**
     * Cache configuration.
     *
     * @var array<string, mixed>
     */
    protected static array $config = self::DEFAULT_CONFIG;

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
     * Switch the active cache driver.
     */
    public static function driver(?CacheDriver $driver = null): CacheDriver
    {
        if ($driver !== null) {
            self::$config['driver'] = $driver;

            self::$driver = CacheFactory::create(
                $driver,
                self::$config
            );
        }

        return self::$config['driver'];
    }

    /**
     * Get the name of the current driver
     */
    public static function driverClass(): string
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
            'driver' => self::driver()->value,
            'driver_class' => self::driverClass(),
            'statistics' => Statistics::all(),
            'php'        => PHP_VERSION,
            'apcu'       => extension_loaded('apcu'),
        ];
    }

    /**
     * Configure the cache library.
     *
     * @param array<string, mixed> $config
     */
    public static function configure(array $config): void
    {
        if (isset($config['driver'])) {
            if (is_string($config['driver'])) {
                $driver = CacheDriver::tryFrom($config['driver']);
                if ($driver === null) {
                    throw new \InvalidArgumentException(
                        "Invalid cache driver '{$config['driver']}'."
                    );
                }
                $config['driver'] = $driver;
            } elseif (!$config['driver'] instanceof CacheDriver) {
                throw new \InvalidArgumentException(
                    'Invalid cache driver.'
                );
            }
        }

        self::$config = array_replace(
            self::$config,
            $config
        );

        self::$driver = null;
    }

    /**
     * Set a single configuration value.
     */
    public static function setConfig(string $key, mixed $value): void
    {
        if ($key === 'driver') {
            if (is_string($value)) {
                $driver = CacheDriver::tryFrom($value);
                if ($driver === null) {
                    throw new \InvalidArgumentException(
                        "Invalid cache driver '{$value}'."
                    );
                }
                $value = $driver;
            } elseif (!$value instanceof CacheDriver) {
                throw new \InvalidArgumentException(
                    'Invalid cache driver.'
                );
            }
        }

        self::$config[$key] = $value;
        self::$driver = null;
    }

    /**
     * Get a configuration value.
     *
     * Returns all configuration values when no key is provided.
     */
    public static function getConfig(
        ?string $key = null,
        mixed $default = null
    ): mixed {
        if ($key === null) {
            return self::$config;
        }

        return self::$config[$key] ?? $default;
    }

    /**
     * Reset the configuration to the default values.
     */
    public static function resetConfig(): void
    {
        self::$config = self::DEFAULT_CONFIG;
        self::$driver = null;
    }

    /**
     * Flush the cache
     */
    public static function flush(): bool
    {
        return self::getDriver()->clear();
    }
}
