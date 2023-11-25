<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\service;

use kuaukutsu\poc\task\dto\TaskViewDto;
use kuaukutsu\poc\task\service\TaskViewer;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;

final class EntityDomainCreator
{
    public function __construct(
        private readonly EntityTaskFactory $taskFactory,
        private readonly TaskViewer $viewer,
    ) {
    }

    /**
     * @param non-empty-string $title
     */
    public function create(DomainIdentity $identity, string $title): TaskViewDto
    {
        $task = $this->taskFactory->create($identity, $title);

        return $this->viewer->get(
            $task->getUuid()
        );
    }
}
