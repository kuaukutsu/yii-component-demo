<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\service;

use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\components\task\TaskViewerFactory;
use kuaukutsu\poc\demo\modules\task\cases\Manage\dto\TaskDomainDto;
use kuaukutsu\poc\task\service\TaskViewer;
use Psr\Container\ContainerExceptionInterface;

final class EntityDomainCreator
{
    private readonly TaskViewer $viewer;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(
        private readonly EntityTaskFactory $taskFactory,
        TaskViewerFactory $viewerFactory,
    ) {
        $this->viewer = $viewerFactory->createByMain();
    }

    /**
     * @param non-empty-string $title
     */
    public function create(DomainIdentity $identity, string $title): TaskDomainDto
    {
        $task = $this->viewer->get(
            $this->taskFactory->create($identity, $title)->getUuid()
        );

        return TaskDomainDto::hydrate(
            $task->toArray()
        );
    }
}
