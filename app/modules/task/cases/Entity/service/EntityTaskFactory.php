<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\service;

use kuaukutsu\poc\task\EntityTask;
use kuaukutsu\poc\task\EntityWrapper;
use kuaukutsu\poc\task\TaskBuilder;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\task\cases\Entity\task\EntityCreateStage;
use kuaukutsu\poc\demo\modules\task\cases\Entity\task\EntityReportStage;

final class EntityTaskFactory
{
    public function __construct(private readonly TaskBuilder $builder)
    {
    }

    /**
     * @param non-empty-string $title
     */
    public function create(DomainIdentity $identity, string $title): EntityTask
    {
        return $this->builder->build(
            $this->builder->create(
                $title,
                new EntityWrapper(
                    class: EntityCreateStage::class,
                    params: [
                        'token' => $identity->getAuthKey(),
                    ]
                ),
                new EntityWrapper(
                    class: EntityReportStage::class,
                ),
            )
        );
    }
}
