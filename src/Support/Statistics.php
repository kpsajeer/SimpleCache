<?php

declare(strict_types=1);

namespace SimpleCache\Support;

class Statistics
{
    protected static int $hits = 0;

    protected static int $misses = 0;

    public static function hit(): void
    {
        self::$hits++;
    }

    public static function miss(): void
    {
        self::$misses++;
    }

    public static function reset(): void
    {
        self::$hits = 0;
        self::$misses = 0;
    }

    /**
     * @return array{
     *     hits:int,
     *     misses:int,
     *     total:int,
     *     hit_rate:float
     * }

    */
    public static function all(): array
    {
        $total = self::$hits + self::$misses;
        return [
            'hits' => self::$hits,
            'misses' => self::$misses,
            'total' => $total,
            'hit_rate' => $total > 0 ? round((self::$hits / $total) * 100, 2) : 0.0,
        ];
    }
}
