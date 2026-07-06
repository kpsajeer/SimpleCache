<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class DriverSwitchTest extends TestCase
{
    public function testSwitchingDriversChangesFacadeImplementation()
    {
        Cache::driver(CacheDriver::ARRAY);
        $this->assertStringContainsString('ArrayDriver', Cache::driverClass());

        if (extension_loaded('apcu')) {
            Cache::driver(CacheDriver::APCU);
            $this->assertStringContainsString('ApcuDriver', Cache::driverClass());
        }
    }
}
