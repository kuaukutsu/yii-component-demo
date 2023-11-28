<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\service;

use Generator;
use yii\db\Expression;
use kuaukutsu\poc\task\dto\StageCollection;
use kuaukutsu\poc\task\dto\StageModel;
use kuaukutsu\poc\task\dto\TaskMetrics;
use kuaukutsu\poc\task\exception\NotFoundException;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\modules\task\models\TaskStage;

use function kuaukutsu\poc\task\tools\entity_hydrator;

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

    public function getMetricsByTask(EntityUuid $taskUuid): TaskMetrics
    {
        $flag = new TaskFlag();

        /**
         * @var array{"count": int} $rows
         */
        $rows = TaskStage::find()
            ->select(
                [
                    'count' => new Expression('COUNT(1)'),
                    'running' => new Expression(
                        'SUM(CASE WHEN flag=:ready OR flag=:running THEN 1 ELSE 0 END)',
                        [
                            ':ready' => $flag->setReady()->toValue(),
                            ':running' => $flag->setRunning()->toValue(),
                        ]
                    ),
                    'waiting' => new Expression(
                        'SUM(CASE WHEN flag=:waiting OR flag=:promise OR flag=:paused THEN 1 ELSE 0 END)',
                        [
                            ':waiting' => $flag->setWaiting()->toValue(),
                            ':promise' => $flag->setPromised()->toValue(),
                            ':paused' => $flag->setPaused()->toValue(),
                        ]
                    ),
                    'success' => new Expression(
                        'SUM(CASE WHEN flag=:success THEN 1 ELSE 0 END)',
                        [
                            ':success' => $flag->setSuccess()->toValue(),
                        ]
                    ),
                    'canceled' => new Expression(
                        'SUM(CASE WHEN flag=:canceled THEN 1 ELSE 0 END)',
                        [
                            ':canceled' => $flag->setCanceled()->toValue(),
                        ]
                    ),
                    'failed' => new Expression(
                        'SUM(CASE WHEN flag>=:error THEN 1 ELSE 0 END)',
                        [
                            ':error' => $flag->setError()->toValue(),
                        ]
                    ),
                ]
            )
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                ]
            )
            ->asArray()
            ->one();

        if ($rows['count'] === 0) {
            return new TaskMetrics();
        }

        return entity_hydrator(TaskMetrics::class, $rows);
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
