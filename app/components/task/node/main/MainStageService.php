<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node\main;

use kuaukutsu\poc\demo\components\task\service\BaseStageService;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\task\dto\StageModel;

final class MainStageService extends BaseStageService
{
    protected function updateAll(array $attributes, array $condition, array $params = []): int
    {
        return TaskMainStage::updateAll($attributes, $condition, $params);
    }

    protected function deleteAll(array $condition, array $params = []): int
    {
        return TaskMainStage::deleteAll($condition, $params);
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    protected function save(PrimaryKeyInterface $pk, array $attributes): StageModel
    {
        $model = $pk->isNewRecord()
            ? new TaskMainStage($pk->getValue())
            : $this->getOne($pk);

        $model->setAttributes($attributes);
        $this->saveSafely($model);
        $model->refresh();

        return $model->toDto();
    }

    /**
     * @throws NotFoundException
     */
    protected function getOne(PrimaryKeyInterface $pk): TaskMainStage
    {
        return TaskMainStage::findOne($pk->getValue())
            ?? throw new NotFoundException(
                strtr('[uuid] TaskMainStage not found.', $pk->getValue())
            );
    }
}
