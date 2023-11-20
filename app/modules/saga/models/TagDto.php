<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use kuaukutsu\ds\dto\DtoBase;
use kuaukutsu\poc\saga\TransactionDataInterface;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class TagDto extends DtoBase implements TransactionDataInterface
{
    /**
     * @var non-empty-string
     */
    public string $uuid;

    /**
     * @var non-empty-string
     */
    public string $name;

    /**
     * @var non-empty-string
     */
    public string $createdAt;
}
