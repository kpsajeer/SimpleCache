<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Config\Config;

class ConfigTest extends TestCase
{
    public function testGetReturnsConfiguredValue()
    {
        $this->assertSame('file', Config::get('driver'));
    }

    public function testGetReturnsDefaultWhenMissing()
    {
        $this->assertSame('fallback', Config::get('missing_key', 'fallback'));
    }
}
