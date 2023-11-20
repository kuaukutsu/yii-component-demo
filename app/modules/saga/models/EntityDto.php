<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use kuaukutsu\ds\dto\DtoBase;
use kuaukutsu\poc\saga\TransactionDataInterface;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class EntityDto extends DtoBase implements TransactionDataInterface
{
    /**
     * @var non-empty-string
     */
    public string $uuid;

    /**
     * @var non-empty-string
     */
    public string $comment;

    public bool $isDeleted;

    /**
     * @var non-empty-string
     */
    public string $createdAt;

    /**
     * @var non-empty-string
     */
    public string $updatedAt;
}
