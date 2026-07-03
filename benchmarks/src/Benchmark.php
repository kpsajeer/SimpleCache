<?php

declare(strict_types=1);

namespace Benchmarks;

class Benchmark
{
    /**
     * Execute a benchmark.
     */
    public function run(
        string $driver,
        string $operation,
        int $iterations,
        callable $callback
    ): BenchmarkResult {

        gc_collect_cycles();

        $startMemory = memory_get_usage(true);
        $peakBefore = memory_get_peak_usage(true);

        $start = hrtime(true);

        $this->repeat($iterations, $callback);

        $end = hrtime(true);

        $peakAfter = memory_get_peak_usage(true);

        $time = ($end - $start) / 1_000_000; // milliseconds

        $opsPerSecond = $time > 0
            ? ($iterations / ($time / 1000))
            : 0.0;

        return new BenchmarkResult(
            driver: $driver,
            operation: $operation,
            iterations: $iterations,
            time: round($time, 3),
            opsPerSecond: round($opsPerSecond),
            memory: max(
                memory_get_usage(true) - $startMemory,
                $peakAfter - $peakBefore
            )
        );
    }

    /**
     * @param callable(int): void $callback
     */
    public function repeat(int $iterations, callable $callback): void
    {
        for ($i = 0; $i < $iterations; $i++) {
            $callback($i);
        }
    }
}