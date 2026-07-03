<?php

declare(strict_types=1);

namespace SimpleCache\Enums;

enum CacheDriver: string
{
    case ARRAY = 'array';
    case APCU = 'apcu';
    case FILE = 'file';
    // Future
    // case REDIS = 'redis';
    // case MEMCACHED = 'memcached';
}
