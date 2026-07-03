<?php

require __DIR__ . '/../vendor/autoload.php';

use SimpleCache\Config\Config;

echo "<pre>";

echo "Driver: ";
var_dump(Config::get('driver'));

echo PHP_EOL;

print_r(Config::all());

echo "</pre>";