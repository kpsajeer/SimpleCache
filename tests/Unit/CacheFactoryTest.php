<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Core\CacheFactory;
use SimpleCache\Enums\CacheDriver;

class CacheFactoryTest extends TestCase
{
    public function testCreateDefaultDriverReturnsCacheDriver()
    {
        $driver = CacheFactory::create();

        $this->assertInstanceOf(CacheDriverInterface::class, $driver);
    }

    public function testCreateArrayDriver()
    {
        $driver = CacheFactory::create(CacheDriver::ARRAY);

        $this->assertInstanceOf(\SimpleCache\Drivers\ArrayDriver::class, $driver);
    }

    public function testCreateFileDriver()
    {
        $driver = CacheFactory::create(CacheDriver::FILE);

        $this->assertInstanceOf(\SimpleCache\Drivers\FileDriver::class, $driver);
    }

    public function testCreateApcuDriver()
    {
        $driver = CacheFactory::create(CacheDriver::APCU);

        $this->assertInstanceOf(\SimpleCache\Drivers\ApcuDriver::class, $driver);
    }
}
