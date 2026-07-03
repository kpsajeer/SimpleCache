<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Cache;
use SimpleCache\Config\Config;

// echo Config::get('path') . PHP_EOL;
// echo Cache::driverName() . PHP_EOL;
// exit;

echo "<pre>";

echo "========== FILE DRIVER TEST ==========\n\n";

/*
|--------------------------------------------------------------------------
| Test put()
|--------------------------------------------------------------------------
*/

echo "1. Testing put()...\n";

Cache::put('username', 'Sajeer', 60);

echo "PASS\n\n";

/*
|--------------------------------------------------------------------------
| Test get()
|--------------------------------------------------------------------------
*/

echo "2. Testing get()...\n";

echo Cache::get('username');

echo "\nPASS\n\n";

/*
|--------------------------------------------------------------------------
| Test has()
|--------------------------------------------------------------------------
*/

echo "3. Testing has()...\n";

var_dump(Cache::has('username'));

echo "\n";

/*
|--------------------------------------------------------------------------
| Test remember()
|--------------------------------------------------------------------------
*/

echo "4. Testing remember()...\n";

$product = Cache::remember(
    'product:1',
    60,
    function () {

        echo "Database Query Executed\n";

        sleep(2);

        return [
            'id' => 1,
            'name' => 'MacBook Pro'
        ];
    }
);

print_r($product);

echo "\n";

/*
|--------------------------------------------------------------------------
| Second remember()
|--------------------------------------------------------------------------
*/

echo "5. Testing remember() again...\n";

$product = Cache::remember(
    'product:1',
    60,
    function () {

        echo "THIS SHOULD NEVER EXECUTE\n";

        return [];
    }
);

print_r($product);

echo "\n";

/*
|--------------------------------------------------------------------------
| Forget
|--------------------------------------------------------------------------
*/

echo "6. Testing forget()...\n";

Cache::forget('username');

var_dump(Cache::has('username'));

echo "\n";

/*
|--------------------------------------------------------------------------
| Clear
|--------------------------------------------------------------------------
*/

echo "7. Testing clear()...\n";

Cache::clear();

var_dump(Cache::has('product:1'));

echo "\n";

echo "========== TEST COMPLETE ==========\n";

echo "</pre>";

print_r(Cache::stats());

print_r(Cache::info());