<?php

declare(strict_types=1);

namespace SimpleCache\Support;

class CachePayload
{
    public function __construct(
        public mixed $value,
        public int $expires = 0
    ) {
    }

    public static function create(
        mixed $value,
        int $ttl = 0
    ): self {

        return new self(
            value: $value,
            expires: $ttl > 0
                ? \time() + $ttl
                : 0
        );
    }

    public function expired(): bool
    {
        return $this->expires !== 0
            && \time() > $this->expires;
    }

    /**
     * @return array{
     *     value: mixed,
     *     expires: int
     * }
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'expires' => $this->expires,
        ];
    }

    /**
     * @param array{
     *     value: mixed,
     *     expires: int
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            value: $data['value'],
            expires: $data['expires']
        );
    }
}
