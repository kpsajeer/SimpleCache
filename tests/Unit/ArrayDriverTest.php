<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ArrayDriverTest extends TestCase
{
    public function testPut()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testGet()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testGetWithDefault()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $this->assertEquals('default', $driver->get('nonexistent', 'default'));
    }

    public function testHas()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertTrue($driver->has('foo'));
        $this->assertFalse($driver->has('nonexistent'));
    }
    public function testForget()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $driver->forget('foo');
        $this->assertFalse($driver->has('foo'));
    }

    public function testClear()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $driver->clear();
        $this->assertFalse($driver->has('foo'));
    }

    public function testRemember()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $value = $driver->remember('foo', 10, function () {
            return 'bar';
        });
        $this->assertEquals('bar', $value);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testForever()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->forever('foo', 'bar');
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testAdd()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->add('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->get('foo'));
        $driver->add('foo', 'baz', 10);
        $this->assertEquals('bar', $driver->get('foo'));
    }

    public function testPull()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $this->assertEquals('bar', $driver->pull('foo'));
        $this->assertFalse($driver->has('foo'));
    }

    public function testMany()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('foo', 'bar', 10);
        $driver->put('baz', 'qux', 10);
        $values = $driver->many(['foo', 'baz']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $values);
    }

    public function testPutMany()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->putMany(['foo' => 'bar', 'baz' => 'qux'], 10);
        $this->assertEquals('bar', $driver->get('foo'));
        $this->assertEquals('qux', $driver->get('baz'));
    }

    public function testIncrement()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('counter', 1, 10);
        $driver->increment('counter');
        $this->assertEquals(2, $driver->get('counter'));
    }

    public function testDecrement()
    {
        $driver = new \SimpleCache\Drivers\ArrayDriver();
        $driver->put('counter', 2, 10);
        $driver->decrement('counter');
        $this->assertEquals(1, $driver->get('counter'));
    }
}
