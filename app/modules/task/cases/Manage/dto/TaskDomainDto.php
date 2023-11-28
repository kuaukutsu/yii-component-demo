<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Manage\dto;

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
    public string $message;

    public array $metrics;

    /**
     * @var non-empty-string
     */
    public string $createdAt;
}
