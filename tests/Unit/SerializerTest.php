<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SimpleCache\Support\CachePayload;
use SimpleCache\Support\Serializer;

class SerializerTest extends TestCase
{
    public function testEncodeDecode()
    {
        $data = ['foo' => 'bar', 'baz' => 'qux'];

        $payload = CachePayload::create($data, 60);
        $encoded = Serializer::encode($payload);
        $decoded = Serializer::decode($encoded);
        $this->assertEquals($payload, $decoded);
    }

    public function testEncodeDecodeNull()
    {
        $data    = null;
        $payload = CachePayload::create($data, 60);
        $encoded = Serializer::encode($payload);
        $decoded = Serializer::decode($encoded);
        $this->assertNull($decoded);
    }

    public function testEncodeDecodeObject()
    {
        $data    = (object) ['foo' => 'bar', 'baz' => 'qux'];
        $payload = CachePayload::create($data, 60);
        $encoded = Serializer::encode($payload);
        $decoded = Serializer::decode($encoded);

        $this->assertNull($decoded);
    }

    public function testEncodeAndDecodeCachePayload(): void
    {
        $payload = CachePayload::create(
            ['foo', 'bar', 'baz'],
            60
        );

        $encoded = Serializer::encode($payload);

        $this->assertIsString($encoded);

        $decoded = Serializer::decode($encoded);

        $this->assertInstanceOf(CachePayload::class, $decoded);
        $this->assertSame($payload->value, $decoded->value);
        $this->assertSame($payload->expires, $decoded->expires);
    }

    public function testInvalidPayload()
    {
        $corruptedPayload = 'not a valid serialized string';
        $this->assertNull(Serializer::decode($corruptedPayload));
    }

    public function testDecodeInvalidPayloadReturnsNull(): void
    {
        $this->assertNull(
            Serializer::decode('invalid')
        );
    }
    public function testDecodeCorruptedPayloadReturnsNull(): void
    {
        $this->assertNull(
            Serializer::decode('a:2:{')
        );
    }

    public function testDecodeRejectsObjects(): void
    {
        $payload = serialize(new \stdClass());

        $this->assertNull(
            Serializer::decode($payload)
        );
    }

    public function testDecodeEmptyStringReturnsNull(): void
    {
        $this->assertNull(
            Serializer::decode('')
        );
    }
}
