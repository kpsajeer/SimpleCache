<?php

declare(strict_types=1);

namespace Tests\Support;

use SimpleCache\Cache;

final class TestConfiguration
{
    public static function reset(): void
    {
        Cache::configure([
            'driver' => 'array',
            'ttl'    => 3600,
            'debug'  => false,
            'path'   => sys_get_temp_dir() . '/simplecache-tests',
        ]);
    }
}
