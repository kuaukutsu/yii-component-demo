<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\service;

use kuaukutsu\poc\task\dto\StageModel;
use kuaukutsu\poc\task\dto\StageModelCreate;
use kuaukutsu\poc\task\dto\StageModelState;
use kuaukutsu\poc\task\service\StageCommand;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\shared\utils\ModelOperationSafely;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidCreate;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\modules\task\models\TaskStage;

final class StageService implements StageCommand
{
    use ModelOperationSafely;

    /**
     * @throws ModelSaveException
     */
    public function create(EntityUuid $uuid, StageModelCreate $model): StageModel
    {
        return $this->save(
            new PrimaryUuidCreate($uuid->getUuid()),
            $model->toArray()
        );
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    public function state(EntityUuid $uuid, StageModelState $model): StageModel
    {
        return $this->save(
            new PrimaryUuidUpdate($uuid->getUuid()),
            $model->toArray()
        );
    }

    /**
     * @throws ModelDeleteException
     */
    public function removeByTask(EntityUuid $taskUuid): bool
    {
        $rows = TaskStage::deleteAll(
            [
                'task_uuid' => $taskUuid->getUuid(),
            ]
        );

        return $rows > 0;
    }

    /**
     * @throws NotFoundException
     * @throws ModelDeleteException
     */
    public function remove(EntityUuid $uuid): bool
    {
        $model = $this->getOne(
            new PrimaryUuidUpdate($uuid->getUuid())
        );

        return $this->deleteSafely($model);
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    private function save(PrimaryKeyInterface $pk, array $attributes): StageModel
    {
        $model = $pk->isNewRecord()
            ? new TaskStage($pk->getValue())
            : $this->getOne($pk);

        $model->setAttributes($attributes);
        $this->saveSafely($model);
        $model->refresh();

        return $model->toDto();
    }

    /**
     * @throws NotFoundException
     */
    private function getOne(PrimaryKeyInterface $pk): TaskStage
    {
        return TaskStage::findOne($pk->getValue())
            ?? throw new NotFoundException(
                strtr('[uuid] TaskStage not found.', $pk->getValue())
            );
    }
}
