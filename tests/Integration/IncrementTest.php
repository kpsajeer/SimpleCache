<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use SimpleCache\Cache;
use SimpleCache\Enums\CacheDriver;

class IncrementTest extends TestCase
{
    protected function setUp(): void
    {
        Cache::setDriver(CacheDriver::ARRAY);
    }

    public function testIncrementAndDecrementViaFacade()
    {
        Cache::forever('counter', 5);
        Cache::increment('counter');
        $this->assertSame(6, Cache::get('counter'));

        Cache::decrement('counter', 3);
        $this->assertSame(3, Cache::get('counter'));
    }
}
