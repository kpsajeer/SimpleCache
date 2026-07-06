<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class PerformanceSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        Cache::driver(CacheDriver::ARRAY);
        Cache::clear();
    }

    public function testBulkPutAndGetPerformanceSmoke()
    {
        for ($i = 0; $i < 10; $i++) {
            Cache::put("key_{$i}", $i, 10);
        }

        $results = Cache::many(array_map(fn ($i) => "key_{$i}", range(0, 9)));

        $this->assertCount(10, $results);
        $this->assertSame(0, $results['key_0']);
        $this->assertSame(9, $results['key_9']);
    }
}
