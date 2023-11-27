<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\task;

use kuaukutsu\poc\task\state\TaskStateInterface;
use kuaukutsu\poc\task\state\TaskStateMessage;
use kuaukutsu\poc\task\TaskHandlerBase;
use kuaukutsu\poc\task\TaskStageContext;
use kuaukutsu\poc\demo\modules\task\cases\Entity\handler\EntitySomeHandler;

final class EntityHandlerStage extends TaskHandlerBase
{
    public function __construct(
        public readonly int $number,
        private readonly EntitySomeHandler $handler,
    ) {
    }

    public function handle(TaskStageContext $context): TaskStateInterface
    {
        $response = $this->handler->handle($this->number);

        return $this->success(
            new TaskStateMessage('Handler success.'),
            $context,
            $response,
        );
    }
}
