<?php

declare(strict_types=1);

namespace SimpleCache\Support;

class Serializer
{
    public static function encode(CachePayload $payload): string
    {
        return \serialize(
            $payload->toArray()
        );
    }

    public static function decode(string $contents): ?CachePayload
    {

        try {
            $data = @\unserialize(
                $contents,
                [
                    'allowed_classes' => false
                ]
            );
        } catch (\Throwable $e) {
            return null;
        }

        if (
            !\is_array($data)
            || !isset($data['value'], $data['expires'])
            || !\is_int($data['expires'])
            || self::containsObject($data['value'])
        ) {
            return null;
        }

        return CachePayload::fromArray($data);
    }

    private static function containsObject(mixed $value): bool
    {
        if (\is_object($value)) {
            return true;
        }

        if (\is_array($value)) {
            foreach ($value as $item) {
                if (self::containsObject($item)) {
                    return true;
                }
            }
        }

        return false;
    }
}
