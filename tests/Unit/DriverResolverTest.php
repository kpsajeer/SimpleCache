<?php

declare (strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
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

    protected function setUp(): void
    {
        Cache::resetConfig();
    }

    public function testResolveFallsBackToArrayForInvalidConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Cache::setConfig('driver', 'invalid');
    }
}
