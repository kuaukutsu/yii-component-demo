<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity;

use Ramsey\Uuid\Uuid;

final class UuidFactory
{
    /**
     * @return non-empty-string
     */
    public function createUuid4(): string
    {
        /**
         * @var non-empty-string
         */
        return Uuid::uuid4()->toString();
    }

    /**
     * @return non-empty-string
     */
    public function createUuid7(): string
    {
        /**
         * @var non-empty-string
         */
        return Uuid::uuid7()->toString();
    }
}
