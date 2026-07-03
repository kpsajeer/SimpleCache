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
        Cache::setDriver(CacheDriver::ARRAY);
        $this->assertStringContainsString('ArrayDriver', Cache::driverName());

        if (extension_loaded('apcu')) {
            Cache::setDriver(CacheDriver::APCU);
            $this->assertStringContainsString('ApcuDriver', Cache::driverName());
        }
    }
}
