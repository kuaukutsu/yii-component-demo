<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\components\entity;

use kuaukutsu\poc\task\dto\StageModel;

interface EntityStageModel
{
    public function toDto(): StageModel;
}
