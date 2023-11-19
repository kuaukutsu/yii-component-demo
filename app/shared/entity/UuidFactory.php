<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity;

use Ramsey\Uuid\UuidFactoryInterface;

final class UuidFactory
{
    public function __construct(private readonly UuidFactoryInterface $uuidFactory)
    {
    }

    /**
     * @return non-empty-string
     */
    public function createUuid4(): string
    {
        return $this->uuidFactory->uuid4()->toString();
    }

    /**
     * @return non-empty-string
     */
    public function createUuid7(): string
    {
        return $this->uuidFactory instanceof \Ramsey\Uuid\UuidFactory
            ? $this->uuidFactory->uuid7()->toString()
            : $this->createUuid4();
    }
}
