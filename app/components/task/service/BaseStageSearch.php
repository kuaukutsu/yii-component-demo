<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\service;

use Generator;
use yii\db\ActiveQuery;
use yii\db\Expression;
use kuaukutsu\poc\task\dto\StageModel;
use kuaukutsu\poc\task\dto\TaskMetrics;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\components\task\components\entity\EntityStageModel;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;

use function kuaukutsu\poc\task\tools\entity_hydrator;

abstract class BaseStageSearch implements StageQuery
{
    abstract protected function find(): ActiveQuery;

    public function getOne(EntityUuid $uuid): StageModel
    {
        return $this->findOne($uuid)
            ?? throw new NotFoundException(
                "[{$uuid->getUuid()}] Stage not found."
            );
    }

    public function findOne(EntityUuid $uuid): ?StageModel
    {
        /** @var EntityStageModel|null $model */
        $model = $this->find()
            ->where($uuid->getQueryCondition())
            ->one();

        return $model?->toDto();
    }

    public function iterableByTask(EntityUuid $taskUuid): Generator
    {
        $query = $this->find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                ]
            )
            ->orderBy('order');

        /** @var EntityStageModel $item */
        foreach ($query->each(1) as $item) {
            yield $item->toDto();
        }
    }

    public function indexReadyByTask(EntityUuid $taskUuid, int $limit): array
    {
        $flag = new TaskFlag();

        /**
         * @var array<array-key, non-empty-string>
         */
        return $this->find()
            ->select('uuid')
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->unset()->setReady()->toValue(),
                        $flag->unset()->setPaused()->toValue(),
                    ],
                ]
            )
            ->limit($limit)
            ->orderBy('order')
            ->column();
    }

    public function findReadyByTask(EntityUuid $taskUuid): ?StageModel
    {
        /** @var EntityStageModel|null $model */
        $model = $this->find()
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

        /** @var EntityStageModel|null $model */
        $model = $this->find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->unset()->setPaused()->toValue(),
                        $flag->unset()->setRunning()->setPaused()->toValue(),
                    ],
                ]
            )
            ->orderBy('order')
            ->limit(1)
            ->one();

        return $model?->toDto();
    }

    public function findForgottenByTask(EntityUuid $taskUuid): ?StageModel
    {
        $flag = new TaskFlag();

        /** @var EntityStageModel|null $model */
        $model = $this->find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => $flag->unset()->setRunning()->toValue(),
                ]
            )
            ->orderBy('order')
            ->limit(1)
            ->one();

        return $model?->toDto();
    }

    public function findPreviousCompletedByTask(EntityUuid $taskUuid, int $stageOrder): ?StageModel
    {
        $flag = new TaskFlag();

        /** @var EntityStageModel|null $model */
        $model = $this->find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->unset()->setSuccess()->toValue(),
                        $flag->unset()->setError()->toValue(),
                    ],
                    'order' => --$stageOrder,
                ]
            )
            ->orderBy('order')
            ->limit(1)
            ->one();

        return $model?->toDto();
    }

    public function existsOpenByTask(EntityUuid $taskUuid): bool
    {
        $flag = new TaskFlag();

        return $this->find()
            ->where(
                [
                    'task_uuid' => $taskUuid->getUuid(),
                    'flag' => [
                        $flag->unset()->setReady()->toValue(),
                        $flag->unset()->setPaused()->toValue(),
                        $flag->unset()->setRunning()->toValue(),
                        $flag->unset()->setRunning()->setPaused()->toValue(),
                    ],
                ]
            )
            ->exists();
    }

    public function getMetricsByTask(EntityUuid $taskUuid): TaskMetrics
    {
        $flag = new TaskFlag();

        /**
         * @var array{"count": int} $rows
         */
        $rows = $this->find()
            ->select(
                [
                    'count' => new Expression('COUNT(1)'),
                    'running' => new Expression(
                        'SUM(CASE WHEN flag=:ready OR flag=:running THEN 1 ELSE 0 END)',
                        [
                            ':ready' => $flag->unset()->setReady()->toValue(),
                            ':running' => $flag->unset()->setRunning()->toValue(),
                        ]
                    ),
                    'waiting' => new Expression(
                        'SUM(CASE WHEN flag=:waiting OR flag=:promise OR flag=:paused THEN 1 ELSE 0 END)',
                        [
                            ':waiting' => $flag->unset()->setWaiting()->toValue(),
                            ':promise' => $flag->unset()->setPromised()->toValue(),
                            ':paused' => $flag->unset()->setPaused()->toValue(),
                        ]
                    ),
                    'success' => new Expression(
                        'SUM(CASE WHEN flag=:success THEN 1 ELSE 0 END)',
                        [
                            ':success' => $flag->unset()->setSuccess()->toValue(),
                        ]
                    ),
                    'canceled' => new Expression(
                        'SUM(CASE WHEN flag=:canceled THEN 1 ELSE 0 END)',
                        [
                            ':canceled' => $flag->unset()->setCanceled()->toValue(),
                        ]
                    ),
                    'failed' => new Expression(
                        'SUM(CASE WHEN flag>=:error THEN 1 ELSE 0 END)',
                        [
                            ':error' => $flag->unset()->setError()->toValue(),
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
}
