<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use kuaukutsu\ds\dto\DtoBase;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class SagaModel extends DtoBase
{
    public ?string $comment = null;

    public ?bool $flag = null;
}
