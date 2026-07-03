<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Drivers\FileDriver;

class FileDriverTest extends TestCase
{
    private FileDriver $driver;

    private string $cacheDir;

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'simple-cache-'
            . uniqid('', true);

        $this->driver = new FileDriver($this->cacheDir);
    }

    protected function tearDown(): void
    {
        if (isset($this->driver)) {
            $this->driver->clear();
        }

        if (is_dir($this->cacheDir)) {
            foreach (glob($this->cacheDir . DIRECTORY_SEPARATOR . '*') as $file) {
                @unlink($file);
            }

            @rmdir($this->cacheDir);
        }
    }

    public function testPut(): void
    {
        $this->assertTrue($this->driver->put('foo', 'bar', 10));
        $this->assertSame('bar', $this->driver->get('foo'));
    }

    public function testGet(): void
    {
        $this->driver->put('foo', 'bar', 10);

        $this->assertSame('bar', $this->driver->get('foo'));
    }

    public function testGetWithDefault(): void
    {
        $this->assertSame(
            'default',
            $this->driver->get('missing', 'default')
        );
    }

    public function testHas(): void
    {
        $this->driver->put('foo', 'bar', 10);

        $this->assertTrue($this->driver->has('foo'));
        $this->assertFalse($this->driver->has('missing'));
    }

    public function testForget(): void
    {
        $this->driver->put('foo', 'bar', 10);

        $this->assertTrue($this->driver->forget('foo'));
        $this->assertFalse($this->driver->has('foo'));
    }

    public function testRemember(): void
    {
        $value = $this->driver->remember(
            'foo',
            10,
            fn () => 'bar'
        );

        $this->assertSame('bar', $value);
        $this->assertSame('bar', $this->driver->get('foo'));
    }

    public function testForever(): void
    {
        $this->assertTrue(
            $this->driver->forever('foo', 'bar')
        );

        $this->assertSame('bar', $this->driver->get('foo'));
    }

    public function testAdd(): void
    {
        $this->assertTrue(
            $this->driver->add('foo', 'bar', 10)
        );

        $this->assertFalse(
            $this->driver->add('foo', 'baz', 10)
        );

        $this->assertSame('bar', $this->driver->get('foo'));
    }

    public function testPull(): void
    {
        $this->driver->put('foo', 'bar', 10);

        $this->assertSame('bar', $this->driver->pull('foo'));
        $this->assertFalse($this->driver->has('foo'));
    }

    public function testMany(): void
    {
        $this->driver->putMany([
            'foo' => 'bar',
            'baz' => 'qux',
        ], 10);

        $this->assertSame(
            [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            $this->driver->many(['foo', 'baz'])
        );
    }

    public function testPutMany(): void
    {
        $this->assertTrue(
            $this->driver->putMany([
                'foo' => 'bar',
                'baz' => 'qux',
            ], 10)
        );

        $this->assertSame('bar', $this->driver->get('foo'));
        $this->assertSame('qux', $this->driver->get('baz'));
    }

    public function testIncrement(): void
    {
        $this->driver->put('counter', 1, 10);

        $this->assertSame(
            2,
            $this->driver->increment('counter')
        );

        $this->assertSame(
            2,
            $this->driver->get('counter')
        );
    }

    public function testDecrement(): void
    {
        $this->driver->put('counter', 2, 10);

        $this->assertSame(
            1,
            $this->driver->decrement('counter')
        );

        $this->assertSame(
            1,
            $this->driver->get('counter')
        );
    }

    public function testTtlExpiration(): void
    {
        $this->driver->put('foo', 'bar', 1);

        sleep(2);

        $this->assertFalse($this->driver->has('foo'));
    }

    public function testDirectoryCreation(): void
    {
        $this->assertTrue(is_dir($this->cacheDir));

        $this->driver->put('foo', 'bar', 10);

        $this->assertSame(
            'bar',
            $this->driver->get('foo')
        );
    }

    public function testClear(): void
    {
        $this->driver->put('foo', 'bar', 10);
        $this->driver->put('baz', 'qux', 10);

        $this->assertTrue($this->driver->clear());

        $this->assertFalse($this->driver->has('foo'));
        $this->assertFalse($this->driver->has('baz'));
    }

    public function testInvalidPath(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FileDriver('');
    }
}
