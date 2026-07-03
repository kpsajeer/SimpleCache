<?php

declare(strict_types=1);

namespace Benchmarks;

class BenchmarkResult
{
    public function __construct(
        public readonly string $driver,
        public readonly string $operation,
        public readonly int $iterations,
        public readonly float $time,
        public readonly float $opsPerSecond,
        public readonly int $memory
    ) {
    }
}