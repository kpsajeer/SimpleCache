<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Benchmarks\Benchmark;
use Benchmarks\TablePrinter;
use SimpleCache\Contracts\CacheDriverInterface;
use SimpleCache\Drivers\ApcuDriver;
use SimpleCache\Drivers\ArrayDriver;
use SimpleCache\Drivers\FileDriver;

const DEFAULT_ITERATIONS = 1000;
const REMEMBER_ITERATIONS = 500;

$benchmarkStart = microtime(true);

echo PHP_EOL;
echo "SimpleCache Benchmark Suite" . PHP_EOL;
echo str_repeat('=', 80) . PHP_EOL;
echo PHP_EOL;

$benchmark = new Benchmark();

$results = [];

$fileCachePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simple-cache-benchmark';

/*
|--------------------------------------------------------------------------
| Drivers
|--------------------------------------------------------------------------
*/

$drivers = [
    'Array' => new ArrayDriver(),
    'File'  => new FileDriver($fileCachePath),
];

if (
    extension_loaded('apcu')
    && (bool) ini_get('apc.enabled')
) {
    $drivers['APCu'] = new ApcuDriver();
}
/*
|--------------------------------------------------------------------------
| Operations
|--------------------------------------------------------------------------
*/

$operations = [

    'put()' => [
        'iterations' => DEFAULT_ITERATIONS,
        'prepare' => null,
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {
            $driver->put("key_$i", "value_$i");
        },
    ],

    'get()' => [
        'iterations' => DEFAULT_ITERATIONS,
        'prepare' => function (CacheDriverInterface $driver): void {

            for ($i = 0; $i < DEFAULT_ITERATIONS; $i++) {
                $driver->put("key_$i", "value_$i");
            }
        },
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {
            $driver->get("key_$i");
        },
    ],

    'remember() miss' => [
        'iterations' => REMEMBER_ITERATIONS,
        'prepare' => null,
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {

            $driver->remember(
                "remember_$i",
                60,
                fn () => "value_$i"
            );
        },
    ],

    'remember() hit' => [
        'iterations' => REMEMBER_ITERATIONS,
        'prepare' => function (CacheDriverInterface $driver): void {

            for ($i = 0; $i < REMEMBER_ITERATIONS; $i++) {
                $driver->put("remember_$i", "value_$i");
            }
        },
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {

            $driver->remember(
                "remember_$i",
                60,
                static fn () => throw new \RuntimeException(
                    'Callback should never execute during remember() hit benchmark.'
                )
            );
        },
    ],

    'increment()' => [
        'iterations' => DEFAULT_ITERATIONS,
        'prepare' => function (CacheDriverInterface $driver): void {
            $driver->put('counter', 0);
        },
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {
            $driver->increment('counter');
        },
    ],

    'decrement()' => [
        'iterations' => DEFAULT_ITERATIONS,
        'prepare' => function (CacheDriverInterface $driver): void {
            $driver->put('counter', DEFAULT_ITERATIONS);
        },
        'callback' => function (
            CacheDriverInterface $driver,
            int $i
        ): void {
            $driver->decrement('counter');
        },
    ],

];

/**
 * Warm up the cache driver.
 */
function warmup(CacheDriverInterface $driver): void
{
    for ($i = 0; $i < 1000; $i++) {
        $driver->put("warmup_$i", $i);
    }

    $driver->clear();
}

/**
 * Remove temporary benchmark files.
 */
function cleanup(string $path): void
{
    if (! is_dir($path)) {
        return;
    }

    foreach (glob($path . DIRECTORY_SEPARATOR . '*.cache') ?: [] as $file) {
        @unlink($file);
    }

    @rmdir($path);
}

/*
|--------------------------------------------------------------------------
| Execute Benchmarks
|--------------------------------------------------------------------------
*/

foreach ($drivers as $driverName => $driver) {
    echo "Benchmarking {$driverName} Driver..." . PHP_EOL;

    warmup($driver);

    foreach ($operations as $operation => $config) {
        echo "  -> {$operation}" . PHP_EOL;

        $driver->clear();
        gc_collect_cycles();

        if ($config['prepare'] !== null) {
            $config['prepare']($driver);
        }

        $results[] = $benchmark->run(
            driver: $driverName,
            operation: $operation,
            iterations: $config['iterations'],
            callback: fn (int $i) => $config['callback']($driver, $i),
        );
    }

    $driver->clear();
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo PHP_EOL;

(new TablePrinter())->print($results);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$totalOperations = array_sum(
    array_map(
        fn (array $config): int => $config['iterations'],
        $operations
    )
);

echo PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;

printf(
    "Drivers Tested : %d\n",
    count($drivers)
);

printf(
    "Operations     : %d\n",
    count($operations)
);

printf(
    "Iterations     : %s\n",
    number_format($totalOperations)
);

printf(
    "Completed      : %.2f seconds\n",
    microtime(true) - $benchmarkStart
);

echo str_repeat('-', 80) . PHP_EOL;

printf("PHP Version : %s\n", PHP_VERSION);
printf("Date        : %s\n", date('Y-m-d H:i:s'));
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Cleanup
|--------------------------------------------------------------------------
*/

cleanup($fileCachePath);

/*
|--------------------------------------------------------------------------
| Future Exporters
|--------------------------------------------------------------------------
*/

// (new MarkdownExporter())->export($results);
// (new CsvExporter())->export($results);
// (new JsonExporter())->export($results);