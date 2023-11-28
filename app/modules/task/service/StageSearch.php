<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\service;

use Generator;
use kuaukutsu\poc\task\dto\StageCollection;
use kuaukutsu\poc\task\dto\StageModel;
use kuaukutsu\poc\task\exception\NotFoundException;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\modules\task\models\TaskStage;

final class StageSearch implements StageQuery
{
    public function getOne(EntityUuid $uuid): StageModel
    {
        /** @var TaskStage|null $model */
        $model = TaskStage::findOne($uuid->getQueryCondition());
        if ($model === null) {
            throw new NotFoundException(
                "[{$uuid->getUuid()}] Stage not found."
            );
        }

        return $model->toDto();
    }

    public function findOne(EntityUuid $uuid): ?StageModel
    {
        /** @var TaskStage|null $model */
        $model = TaskStage::findOne($uuid->getQueryCondition());
        return $model?->toDto();
    }

    public function findByTask(EntityUuid $taskUuid): Generator
    {
        $query = TaskStage::find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                ]
            );

        /** @var TaskStage $item */
        foreach ($query->each(1) as $item) {
            yield $item->toDto();
        }
    }

    public function getOpenByTask(EntityUuid $taskUuid): StageCollection
    {
        $flag = new TaskFlag();

        /** @var TaskStage[] $models */
        $models = TaskStage::find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->setReady()->toValue(),
                        $flag->setRunning()->toValue(),
                        $flag->setWaiting()->toValue(),
                    ],
                ]
            )
            ->orderBy('order')
            ->all();

        $collecton = new StageCollection();
        foreach ($models as $model) {
            $collecton->attach(
                $model->toDto()
            );
        }

        return $collecton;
    }

    public function getPromiseByTask(EntityUuid $taskUuid): StageCollection
    {
        $flag = new TaskFlag();

        /** @var TaskStage[] $models */
        $models = TaskStage::find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->setPromised()->toValue(),
                    ],
                ]
            )
            ->orderBy('order')
            ->all();

        $collecton = new StageCollection();
        foreach ($models as $model) {
            $collecton->attach(
                $model->toDto()
            );
        }

        return $collecton;
    }

    public function findReadyByTask(EntityUuid $taskUuid): ?StageModel
    {
        /** @var TaskStage|null $model */
        $model = TaskStage::find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => (new TaskFlag())->setReady()->toValue(),
                ]
            )
            ->orderBy('order')
            ->limit(1)
            ->one();

        return $model?->toDto();
    }

    public function findPausedByTask(EntityUuid $taskUuid): ?StageModel
    {
        $flag = new TaskFlag();

        /** @var TaskStage|null $model */
        $model = TaskStage::find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->setPaused()->toValue(),
                        $flag->setRunning()->setPaused()->toValue(),
                    ],
                ]
            )
            ->orderBy('order')
            ->limit(1)
            ->one();

        return $model?->toDto();
    }
}
