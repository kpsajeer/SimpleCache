<?php

echo "<pre>";

echo "PHP: " . PHP_BINARY . PHP_EOL;

echo "APCu Loaded: ";
var_dump(\extension_loaded('apcu'));

echo "APCu Enabled: ";
var_dump(\ini_get('apc.enabled'));

echo "APCu CLI: ";
var_dump(\ini_get('apc.enable_cli'));

echo "</pre>";