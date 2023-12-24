<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity;

use kuaukutsu\poc\task\TaskResponseInterface;

final readonly class EntityNumber implements TaskResponseInterface
{
    public function __construct(public int $number)
    {
    }
}
