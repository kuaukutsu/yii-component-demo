<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\task;

use kuaukutsu\poc\task\state\TaskStateInterface;
use kuaukutsu\poc\task\state\TaskStateMessage;
use kuaukutsu\poc\task\TaskHandlerBase;
use kuaukutsu\poc\task\TaskStageContext;

final class EntityReportStage extends TaskHandlerBase
{
    public function handle(TaskStageContext $context): TaskStateInterface
    {
        $response = $this->preparePrevious($context)->getResponse();
        if (!$response instanceof EntityResponse) {
            return $this->error(
                new TaskStateMessage('Previous Response must implement EntityResponse.'),
            );
        }

        return $this->success(
            new TaskStateMessage($response->comment),
        );
    }
}
