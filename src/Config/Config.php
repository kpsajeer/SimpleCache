<?php

declare (strict_types=1);

namespace SimpleCache\Config;

class Config
{
    /** @var array<string, mixed> */
    protected static array $config = [];

    protected static bool $loaded = false;

    protected static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        self::$config = require __DIR__ . '/cache.php';

        self::$loaded = true;
    }

    /**
     * Set a configuration value.
     */
    public static function set(string $key, mixed $value): void
    {
        self::load();

        self::$config[$key] = $value;
    }

    /**
     * Replace all configuration values.
     *
     * @param array<string, mixed> $config
     */
    public static function replace(array $config): void
    {
        self::$config = $config;
        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        return self::$config[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        self::load();

        return self::$config;
    }
}
