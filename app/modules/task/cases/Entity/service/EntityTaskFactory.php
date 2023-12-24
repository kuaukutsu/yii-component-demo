<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\service;

use Psr\Container\ContainerExceptionInterface;
use kuaukutsu\poc\task\EntityTask;
use kuaukutsu\poc\task\EntityWrapper;
use kuaukutsu\poc\task\TaskBuilder;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\components\task\TaskBuilderFactory;
use kuaukutsu\poc\demo\modules\task\cases\Entity\task\EntityCreateStage;
use kuaukutsu\poc\demo\modules\task\cases\Entity\task\EntityPromiseStage;
use kuaukutsu\poc\demo\modules\task\cases\Entity\task\EntityReportStage;

final class EntityTaskFactory
{
    private readonly TaskBuilder $builder;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(TaskBuilderFactory $factory)
    {
        $this->builder = $factory->createByMain();
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
                        'identity' => $identity,
                    ]
                ),
                new EntityWrapper(
                    class: EntityReportStage::class,
                ),
                new EntityWrapper(
                    class: EntityPromiseStage::class,
                ),
            )
        );
    }
}
