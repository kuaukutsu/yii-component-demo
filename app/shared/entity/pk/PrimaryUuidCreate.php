<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity\pk;

use Ramsey\Uuid\Uuid;

final class PrimaryUuidCreate implements PrimaryKeyInterface
{
    /**
     * @var string Format 00000000-0000-0000-0000-000000000000
     */
    private readonly string $uuid;

    /**
     * @param string|null $uuid Format 00000000-0000-0000-0000-000000000000
     */
    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid7()->toString();
    }

    public function isNewRecord(): bool
    {
        return true;
    }

    /**
     * @return array{uuid: string}
     */
    public function getValue(): array
    {
        return [
            'uuid' => $this->uuid,
        ];
    }
}
