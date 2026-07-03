<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Support\Statistics;

class StatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        Statistics::reset();
    }

    /*


remember()

get()

all()
    */

    public function testHitMissAndReset()
    {
        Statistics::hit();
        Statistics::miss();

        $stats = Statistics::all();

        $this->assertSame(1, $stats['hits']);
        $this->assertSame(1, $stats['misses']);
        $this->assertSame(2, $stats['total']);
        $this->assertSame(50.0, $stats['hit_rate']);
    }

    public function testReset()
    {
        Statistics::hit();
        Statistics::miss();

        Statistics::reset();

        $stats = Statistics::all();

        $this->assertSame(0, $stats['hits']);
        $this->assertSame(0, $stats['misses']);
        $this->assertSame(0, $stats['total']);
        $this->assertSame(0.0, $stats['hit_rate']);
    }

    public function testAll()
    {
        $stats = Statistics::all();

        $this->assertSame(0, $stats['hits']);
        $this->assertSame(0, $stats['misses']);
        $this->assertSame(0, $stats['total']);
        $this->assertSame(0.0, $stats['hit_rate']);
    }
}
