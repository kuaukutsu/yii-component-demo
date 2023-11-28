<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\dto;

use kuaukutsu\ds\dto\DtoBase;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class TaskDomainDto extends DtoBase
{
    /**
     * @var non-empty-string
     */
    public string $uuid;

    /**
     * @var non-empty-string
     */
    public string $state;

    /**
     * @var non-empty-string
     */
    public string $createdAt;
}
