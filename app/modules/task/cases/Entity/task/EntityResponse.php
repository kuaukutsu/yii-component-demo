<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\task;

use kuaukutsu\ds\dto\DtoBase;
use kuaukutsu\poc\task\TaskResponseInterface;

/**
 * @psalm-immutable
 * @psalm-suppress MissingConstructor
 */
final class EntityResponse extends DtoBase implements TaskResponseInterface
{
    /**
     * @var non-empty-string
     */
    public string $uuid;

    /**
     * @var non-empty-string
     */
    public string $comment;
}
