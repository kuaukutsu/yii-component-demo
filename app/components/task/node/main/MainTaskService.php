<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node\main;

use kuaukutsu\poc\task\dto\TaskModel;
use kuaukutsu\poc\demo\components\task\service\BaseTaskService;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;

final class MainTaskService extends BaseTaskService
{
    protected function updateAll(array $attributes, array $condition, array $params = []): int
    {
        return TaskMain::updateAll($attributes, $condition, $params);
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    protected function save(PrimaryKeyInterface $pk, array $attributes): TaskModel
    {
        $model = $pk->isNewRecord()
            ? new TaskMain($pk->getValue())
            : $this->getOne($pk);

        $model->setAttributes($attributes);
        $this->saveSafely($model);
        $model->refresh();

        return $model->toDto();
    }

    /**
     * @throws NotFoundException
     */
    protected function getOne(PrimaryKeyInterface $pk): TaskMain
    {
        return TaskMain::findOne($pk->getValue())
            ?? throw new NotFoundException(
                strtr('[uuid] TaskMain not found.', $pk->getValue())
            );
    }
}
