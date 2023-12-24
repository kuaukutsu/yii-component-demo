<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\task;

use kuaukutsu\poc\task\EntityWrapper;
use kuaukutsu\poc\task\state\TaskStateInterface;
use kuaukutsu\poc\task\TaskBuilder;
use kuaukutsu\poc\task\TaskHandlerBase;
use kuaukutsu\poc\task\TaskStageContext;

final class EntityPromiseStage extends TaskHandlerBase
{
    /**
     * @param int[] $index
     */
    public function __construct(
        private readonly TaskBuilder $builder,
        private readonly array $index = [1, 2, 3, 4, 5],
    ) {
    }

    public function handle(TaskStageContext $context): TaskStateInterface
    {
        $this->preparePrevious($context);

        $task = $this->builder
            ->create("[$context->task] nested task.")
            ->setTimeout(10);

        foreach ($this->index as $item) {
            $task->addStage(
                new EntityWrapper(
                    class: EntityHandlerStage::class,
                    params: [
                        'number' => $item,
                    ]
                ),
            );
        }

        return $this->wait(
            $this->builder->build($task, $context),
            $context
        );
    }
}
