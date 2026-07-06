<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Cache;

Cache::configure([
    'driver' => 'array',
    'ttl' => 120,
    'debug' => true,
]);

// Basic put/get
Cache::put('name', 'Jane', 300);
echo "get(name): " . Cache::get('name') . PHP_EOL;

echo "has(name): ";
var_dump(Cache::has('name'));

// Add only if missing
Cache::forget('visits');
Cache::add('visits', 1, 300);
Cache::add('visits', 2, 300);

echo "visits after add: " . Cache::get('visits') . PHP_EOL;

// Remember
$value = Cache::remember('counter', 300, fn () => 100);
echo "remember(counter): " . $value . PHP_EOL;

// Increment / decrement
Cache::increment('counter');
Cache::decrement('counter');
echo "counter after inc/dec: " . Cache::get('counter') . PHP_EOL;

// Many / putMany
Cache::putMany([
    'city' => 'Paris',
    'country' => 'France',
], 300);

$data = Cache::many(['name', 'city', 'country']);
print_r($data);

// Pull
echo "pull(name): " . Cache::pull('name') . PHP_EOL;

// Forever
Cache::forever('app_name', 'SimpleCache');
echo "forever(app_name): " . Cache::get('app_name') . PHP_EOL;

// Configuration helpers
echo "driver name: " . Cache::driverName() . PHP_EOL;
echo "current ttl: " . Cache::getConfig('ttl') . PHP_EOL;

echo "stats: ";
print_r(Cache::stats());

echo "info: ";
print_r(Cache::info());

// Flush cache
Cache::flush();

echo "after flush has city? ";
var_dump(Cache::has('city'));
