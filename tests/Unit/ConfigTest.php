<?php

declare (strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Core\CacheFactory;
use SimpleCache\Enums\CacheDriver;

class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        Cache::resetConfig();
    }

    public function testGetReturnsConfiguredValue(): void
    {
        Cache::setConfig('driver', 'file');
        $this->assertSame(CacheDriver::FILE, Cache::getConfig('driver'));
    }

    public function testGetReturnsDefaultWhenMissing(): void
    {
        $this->assertSame(
            'fallback',
            Cache::getConfig('missing_key', 'fallback')
        );
    }

    public function testConfigureUpdatesMultipleValues(): void
    {
        Cache::configure([
            'driver' => 'apcu',
            'ttl'    => 600,
        ]);

        $this->assertSame(CacheDriver::APCU, Cache::getConfig('driver'));
        $this->assertSame(600, Cache::getConfig('ttl'));
    }

    public function testAllReturnsConfiguration(): void
    {
        $config = Cache::getConfig();

        $this->assertArrayHasKey('driver', $config);
        $this->assertArrayHasKey('ttl', $config);
        $this->assertArrayHasKey('debug', $config);
    }

    public function testThrowsExceptionWhenFileDriverPathIsMissing(): void
    {
        Cache::configure([
            'driver' => 'file',
        ]);

        Cache::setConfig('path', null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File cache path is required.');

        CacheFactory::create();
    }

    public function testThrowsExceptionWhenFileDriverPathIsEmpty(): void
    {
        Cache::configure([
            'driver' => 'file',
            'path'   => '',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File cache path is required.');

        CacheFactory::create();
    }
}
