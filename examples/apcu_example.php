<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Cache;

Cache::configure([
    'driver' => 'apcu',
    'ttl' => 300,
]);

Cache::put('counter', 1, 300);
Cache::increment('counter');

echo "APCu counter: " . Cache::get('counter') . PHP_EOL;

echo "APCu enabled: " . (extension_loaded('apcu') ? 'yes' : 'no') . PHP_EOL;
