<?php

declare(strict_types=1);

namespace SimpleCache\Core;

use SimpleCache\Config\Config;
use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Drivers\ApcuDriver;
use SimpleCache\Drivers\ArrayDriver;
use SimpleCache\Drivers\FileDriver;
use SimpleCache\Enums\CacheDriver;

class CacheFactory
{
    public static function create(?CacheDriver $driver = null): CacheDriverInterface
    {
        $driver = DriverResolver::resolve($driver);
        if ($driver === CacheDriver::ARRAY) {
            return new ArrayDriver();
        }

        if ($driver === CacheDriver::APCU) {
            return new ApcuDriver();
        }

        return new FileDriver(
            Config::get('path')
        );
    }
}
