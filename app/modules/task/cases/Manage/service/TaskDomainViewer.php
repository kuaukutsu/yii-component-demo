<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Manage\service;

use Psr\Container\ContainerExceptionInterface;
use kuaukutsu\poc\task\service\TaskViewer;
use kuaukutsu\poc\demo\components\task\TaskViewerFactory;
use kuaukutsu\poc\demo\modules\task\cases\Manage\dto\TaskDomainDto;

final readonly class TaskDomainViewer
{
    private TaskViewer $viewer;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(private TaskViewerFactory $factory)
    {
        $this->viewer = $this->factory->createByMain();
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
