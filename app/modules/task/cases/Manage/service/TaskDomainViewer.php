<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Manage\service;

use kuaukutsu\poc\task\service\TaskViewer;
use kuaukutsu\poc\demo\modules\task\cases\Manage\dto\TaskDomainDto;

final class TaskDomainViewer
{
    public function __construct(private readonly TaskViewer $viewer)
    {
    }

    /**
     * @param non-empty-string $taskUuid
     */
    public function view(string $taskUuid): TaskDomainDto
    {
        $task = $this->viewer->get($taskUuid);

        return TaskDomainDto::hydrate(
            $task->toArray()
        );
    }
}
