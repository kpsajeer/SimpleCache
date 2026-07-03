<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class RememberTest extends TestCase
{
    protected function setUp(): void
    {
        Cache::setDriver(CacheDriver::ARRAY);
    }

    public function testRememberStoresAndReturnsValue()
    {
        $value = Cache::remember('remember_key', 10, function () {
            return 'remembered';
        });

        $this->assertSame('remembered', $value);
        $this->assertSame('remembered', Cache::get('remember_key'));
    }
}
