<?php

declare (strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Config\Config;
use SimpleCache\Core\DriverResolver;
use SimpleCache\Enums\CacheDriver;

class DriverResolverTest extends TestCase
{
    public function testResolveArrayDriver()
    {
        $this->assertSame(CacheDriver::ARRAY, DriverResolver::resolve(CacheDriver::ARRAY));
    }

    public function testResolveApcuDriverIfAvailable()
    {
        $expected = extension_loaded('apcu') ? CacheDriver::APCU : CacheDriver::FILE;

        $this->assertSame($expected, DriverResolver::resolve(CacheDriver::APCU));
    }

    public function testResolveFallsBackToArrayForInvalidConfig(): void
    {
        Config::set('driver', 'invalid');

        $this->assertSame(
            CacheDriver::ARRAY,
            DriverResolver::resolve()
        );
    }
}
