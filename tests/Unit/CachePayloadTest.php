<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Support\CachePayload;

class CachePayloadTest extends TestCase
{
    public function testCreateToArrayAndFromArray()
    {
        $payload = CachePayload::create(['foo' => 'bar'], 60);
        $array = $payload->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('expires', $array);

        $decoded = CachePayload::fromArray($array);

        $this->assertSame($payload->value, $decoded->value);
        $this->assertSame($payload->expires, $decoded->expires);
    }

    public function testExpiredReturnsTrueForPastExpiration()
    {
        $payload = new CachePayload('foo', time() - 1);

        $this->assertTrue($payload->expired());
    }

    public function testRemainingTtl()
    {
        $now = time();
        $payload = new CachePayload('foo', $now + 60);
        $this->assertSame(60, $payload->expires - $now);
    }

    public function testSetTtl()
    {
        $now = time();
        $payload = new CachePayload('foo', $now + 60);
        $payload->expires = $now + 120;
        $this->assertSame(120, $payload->expires - $now);
    }

    public function testForever()
    {
        $payload = new CachePayload('foo');
        $this->assertSame(0, $payload->expires);
    }
}
