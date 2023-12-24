<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\components\entity;

use kuaukutsu\poc\task\dto\TaskModel;

interface EntityTaskModel
{
    public function toDto(): TaskModel;
}
