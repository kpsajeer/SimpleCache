<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class CacheFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        Cache::setDriver(CacheDriver::ARRAY);
    }

    public function testPutAndGetThroughFacade()
    {
        Cache::put('foo', 'bar', 10);

        $this->assertSame('bar', Cache::get('foo'));
    }

    public function testDriverNameReturnsClassName()
    {
        Cache::setDriver(CacheDriver::ARRAY);

        $this->assertStringContainsString('ArrayDriver', Cache::driverName());
    }
}
