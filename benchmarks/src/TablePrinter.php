<?php

declare(strict_types=1);

namespace Benchmarks;

class TablePrinter
{
    /**
     * @param BenchmarkResult[] $results
     */
    public function print(array $results): void
    {
        $headers = [
            'Driver',
            'Operation',
            'Iterations',
            'Time (ms)',
            'Ops/sec',
            'Memory',
        ];

        $rows = [];

        foreach ($results as $result) {
            $rows[] = [
                $result->driver,
                $result->operation,
                number_format($result->iterations),
                number_format($result->time, 3),
                number_format($result->opsPerSecond),
                $this->formatBytes($result->memory),
            ];
        }

        $widths = [];

        foreach ($headers as $i => $header) {
            $widths[$i] = strlen($header);
        }

        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $widths[$i] = max($widths[$i], strlen($cell));
            }
        }

        $this->separator($widths);
        $this->row($headers, $widths);
        $this->separator($widths);

        foreach ($rows as $row) {
            $this->row($row, $widths);
        }

        $this->separator($widths);
    }

    /**
     * @param string[] $row
     * @param int[] $widths
     */
    private function row(array $row, array $widths): void
    {
        echo '|';

        foreach ($row as $i => $cell) {
            echo ' ' . str_pad($cell, $widths[$i]) . ' |';
        }

        echo PHP_EOL;
    }

    /**
     * @param int[] $widths
     */
    private function separator(array $widths): void
    {
        echo '+';

        foreach ($widths as $width) {
            echo str_repeat('-', $width + 2) . '+';
        }

        echo PHP_EOL;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return number_format($bytes / 1024 / 1024, 2) . ' MB';
    }
}