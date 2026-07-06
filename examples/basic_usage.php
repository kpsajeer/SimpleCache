<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Cache;

Cache::configure([
    'driver' => 'array',
    'ttl' => 120,
    'debug' => true,
]);

Cache::put('username', 'Jane', 300);

echo "Stored: " . Cache::get('username') . PHP_EOL;

echo "Has username? ";
var_dump(Cache::has('username'));

echo "Stats: ";
print_r(Cache::stats());

echo "Info: ";
print_r(Cache::info());
