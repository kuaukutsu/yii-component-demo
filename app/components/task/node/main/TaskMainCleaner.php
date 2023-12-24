<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node\main;

final class TaskMainCleaner
{
    public function clearOlderThanFiveDays(): void
    {
        TaskMainStage::deleteAll(
            "created_at < (current_date - interval '5 day')"
        );

        TaskMain::deleteAll(
            "created_at < (current_date - interval '5 day')"
        );
    }
}
