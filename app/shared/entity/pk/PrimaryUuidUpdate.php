<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity\pk;

final readonly class PrimaryUuidUpdate implements PrimaryKeyInterface
{
    /**
     * @param string $uuid Format 00000000-0000-0000-0000-000000000000
     */
    public function __construct(private string $uuid)
    {
    }

    public function isNewRecord(): bool
    {
        return false;
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
