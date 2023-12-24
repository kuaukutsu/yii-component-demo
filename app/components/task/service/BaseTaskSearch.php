<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\service;

use yii\db\ActiveQuery;
use kuaukutsu\poc\task\dto\TaskCollection;
use kuaukutsu\poc\task\dto\TaskModel;
use kuaukutsu\poc\task\service\TaskQuery;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\components\task\components\entity\EntityTaskModel;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;

abstract class BaseTaskSearch implements TaskQuery
{
    abstract protected function find(): ActiveQuery;

    public function getOne(EntityUuid $uuid): TaskModel
    {
        /** @var EntityTaskModel|null $model */
        $model = $this->find()
            ->where($uuid->getQueryCondition())
            ->one();

        if ($model === null) {
            throw new NotFoundException(
                "[{$uuid->getUuid()}] Task not found."
            );
        }

        return $model->toDto();
    }

    public function getReady(int $limit): TaskCollection
    {
        $flag = new TaskFlag();
        $query = $this->find()
            ->where(
                [
                    'flag' => $flag->unset()->setReady()->toValue(),
                ]
            )
            ->orderBy('created_at')
            ->limit($limit);

        $collection = new TaskCollection();
        /** @var EntityTaskModel $model */
        foreach ($query->each($limit) as $model) {
            $collection->attach(
                $model->toDto()
            );
        }

        return $collection;
    }

    public function getPromise(int $limit): TaskCollection
    {
        $flag = new TaskFlag();
        $query = $this->find()
            ->where(
                [
                    'flag' => $flag->unset()->setPromised()->toValue(),
                ]
            )
            ->orderBy('created_at')
            ->limit($limit);

        $collection = new TaskCollection();
        /** @var EntityTaskModel $model */
        foreach ($query->each($limit) as $model) {
            $collection->attach(
                $model->toDto()
            );
        }

        return $collection;
    }

    public function getForgotten(int $limit): TaskCollection
    {
        $flag = new TaskFlag();
        $query = $this->find()
            ->where(
                [
                    'flag' => [
                        $flag->unset()->setRunning()->toValue(),
                        $flag->unset()->setPromised()->toValue(),
                    ],
                ]
            )
            ->andWhere(
                [
                    '>',
                    'created_at',
                    gmdate('Y-m-d H:i:s', strtotime('-1 day')),
                ]
            )
            ->orderBy('created_at')
            ->limit($limit);

        $collection = new TaskCollection();
        /** @var EntityTaskModel $model */
        foreach ($query->each($limit) as $model) {
            $collection->attach(
                $model->toDto()
            );
        }

        return $collection;
    }

    public function getPaused(int $limit): TaskCollection
    {
        $flag = new TaskFlag();
        $query = $this->find()
            ->where(
                [
                    'flag' => [
                        $flag->unset()->setPaused()->toValue(),
                        $flag->unset()->setRunning()->setPaused()->toValue(),
                    ],
                ]
            )
            ->orderBy('created_at')
            ->limit($limit);

        $collection = new TaskCollection();
        /** @var EntityTaskModel $model */
        foreach ($query->each($limit) as $model) {
            $collection->attach(
                $model->toDto()
            );
        }
        return $collection;
    }

    public function existsOpenByChecksum(string $checksum): bool
    {
        $flag = new TaskFlag();

        return $this->find()
            ->where(
                [
                    'checksum' => $checksum,
                    'flag' => [
                        $flag->unset()->setReady()->toValue(),
                        $flag->unset()->setPaused()->toValue(),
                        $flag->unset()->setRunning()->toValue(),
                        $flag->unset()->setRunning()->setPaused()->toValue(),
                        $flag->unset()->setWaiting()->toValue(),
                        $flag->unset()->setPromised()->toValue(),
                    ],
                ]
            )
            ->exists();
    }
}
