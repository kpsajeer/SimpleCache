<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Cache;

Cache::configure([
    'driver' => 'file',
    'path' => __DIR__ . '/../storage/cache',
    'ttl' => 300,
]);

Cache::put('page_title', 'SimpleCache Example', 300);

echo "Stored file cache value:\n";
echo Cache::get('page_title') . PHP_EOL;

echo "File cache directory: " . Cache::getConfig('path') . PHP_EOL;
