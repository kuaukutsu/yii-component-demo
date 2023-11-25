<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\service;

use kuaukutsu\poc\demo\modules\task\models\Task;
use kuaukutsu\poc\task\dto\TaskCollection;
use kuaukutsu\poc\task\dto\TaskDto;
use kuaukutsu\poc\task\exception\NotFoundException;
use kuaukutsu\poc\task\service\TaskQuery;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\task\state\TaskFlag;

final class TaskSearch implements TaskQuery
{
    public function getOne(EntityUuid $uuid): TaskDto
    {
        /** @var Task|null $model */
        $model = Task::findOne($uuid->getQueryCondition());
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

        /** @var Task[] $models */
        $models = Task::find()
            ->where(
                [
                    'flag' => $flag->setReady()->toValue(),
                ]
            )
            ->limit($limit)
            ->all();

        $collecton = new TaskCollection();
        foreach ($models as $model) {
            $collecton->attach(
                $model->toDto()
            );
        }

        return $collecton;
    }

    public function getPaused(int $limit): TaskCollection
    {
        $flag = new TaskFlag();

        /** @var Task[] $models */
        $models = Task::find()
            ->where(
                [
                    'flag' => [
                        $flag->setPaused()->toValue(),
                        $flag->setRunning()->setPaused()->toValue(),
                    ],
                ]
            )
            ->limit($limit)
            ->all();

        $collecton = new TaskCollection();
        foreach ($models as $model) {
            $collecton->attach(
                $model->toDto()
            );
        }

        return $collecton;
    }
}
