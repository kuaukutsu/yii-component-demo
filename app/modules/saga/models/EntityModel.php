<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use kuaukutsu\ds\dto\DtoBase;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class EntityModel extends DtoBase
{
    public ?string $comment = null;

    public ?bool $is_deleted = null;
}
