<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity;

use kuaukutsu\poc\task\TaskResponseInterface;

final class EntityNumber implements TaskResponseInterface
{
    public function __construct(public readonly int $number)
    {
    }
}
