<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestConfiguration;

class ApcuDriverTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('apcu') || !filter_var(ini_get('apc.enable_cli'), FILTER_VALIDATE_BOOL)) {
            $this->markTestSkipped('APCu CLI is not enabled.');
        }

        TestConfiguration::reset();

        \apcu_clear_cache();
    }

    public function testPut()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testGet()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testGetWithDefault()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->assertEquals('default', $driver->get('nonexistent', 'default'));
    }

    public function testHas()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertTrue($driver->has('foo'));
        $this->assertFalse($driver->has('nonexistent'));
    }
    public function testForget()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $driver->forget('foo');
        $this->assertFalse($driver->has('foo'));
    }

    public function testRemember()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $value  = $driver->remember('foo', 10, function () {
            return 'bar';
        });
        $this->assertEquals('bar', $value);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testForever()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->forever('foo', 'bar');
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testAdd()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->add('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
        $driver->add('foo', 'baz', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testPull()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->pull('foo'));
        $this->assertFalse($driver->has('foo'));
    }

    public function testMany()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $driver->put('baz', 'qux', 10);
        $values = $driver->many(['foo', 'baz']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $values);
    }

    public function testPutMany()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->putMany(['foo' => 'bar', 'baz' => 'qux'], 10);
        $this->assertEquals('bar', $driver->get('foo'));
        $this->assertEquals('qux', $driver->get('baz'));
    }

    public function testIncrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('counter', 1, 10);
        $driver->increment('counter');
        $this->assertEquals(2, $driver->get('counter'));
    }

    public function testDecrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('counter', 2, 10);
        $driver->decrement('counter');
        $this->assertEquals(1, $driver->get('counter'));
    }

    public function testTtlExpiration()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 1); // 1 second TTL
        sleep(2);                      // wait for expiration
        $this->assertFalse($driver->has('foo'));
    }


    public function testClear()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $driver->put('baz', 'qux', 10);
        $driver->clear();
        $this->assertFalse($driver->has('foo'));
        $this->assertFalse($driver->has('baz'));
    }

    public function testAtomicIncrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('counter', 1, 10);
        $driver->increment('counter', 5);
        $this->assertEquals(6, $driver->get('counter'));
    }

    public function testAtomicDecrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('counter', 10, 10);
        $driver->decrement('counter', 3);
        $this->assertEquals(7, $driver->get('counter'));
    }

    public function testIncrementNonInteger()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->expectException(\InvalidArgumentException::class);
        $driver->increment('foo');
    }

    public function testDecrementNonInteger()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->put('foo', 'bar', 10);
        $this->expectException(\InvalidArgumentException::class);
        $driver->decrement('foo');
    }

    public function testIncrementNonexistentKey()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->assertEquals(1, $driver->increment('new_counter'));
    }

    public function testDecrementNonexistentKey()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->assertEquals(-1, $driver->decrement('new_counter'));
    }

    public function testInvalidKey()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->expectException(\InvalidArgumentException::class);
        $driver->put('', 'value', 10);
    }

    public function testInvalidKeyIncrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->expectException(\InvalidArgumentException::class);
        $driver->increment('');
    }

    public function testInvalidKeyDecrement()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $this->expectException(\InvalidArgumentException::class);
        $driver->decrement('');
    }

    public function testTtlExpirationIncrementedKey()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->increment('temp_counter', 1);
        $driver->put('temp_counter', $driver->get('temp_counter'), 1); // set TTL
        sleep(2); // wait for expiration
        $this->assertFalse($driver->has('temp_counter'));
    }

    public function testTtlExpirationDecrementedKey()
    {
        $driver = new \SimpleCache\Drivers\ApcuDriver();
        $driver->decrement('temp_counter', 1);
        $driver->put('temp_counter', $driver->get('temp_counter'), 1); // set TTL
        sleep(2); // wait for expiration
        $this->assertFalse($driver->has('temp_counter'));
    }


}
