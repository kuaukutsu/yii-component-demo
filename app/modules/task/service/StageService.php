<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\service;

use kuaukutsu\poc\task\dto\StageDto;
use kuaukutsu\poc\task\dto\StageModel;
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
    public function create(EntityUuid $uuid, StageModel $model): StageDto
    {
        return $this->save(
            new PrimaryUuidCreate($uuid->getUuid()),
            $model->toArrayRecursive()
        );
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    public function update(EntityUuid $uuid, StageModel $model): StageDto
    {
        return $this->save(
            new PrimaryUuidUpdate($uuid->getUuid()),
            $model->toArrayRecursive()
        );
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    public function replace(EntityUuid $uuid, StageDto $model): bool
    {
        $rows = TaskStage::updateAll(
            [
                'flag' => $model->flag,
                'state' => $model->state,
                'order' => $model->order,
                'updated_at' => gmdate('c'),
            ],
            $uuid->getQueryCondition(),
        );

        return $rows > 0;
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
    private function save(PrimaryKeyInterface $pk, array $attributes): StageDto
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
