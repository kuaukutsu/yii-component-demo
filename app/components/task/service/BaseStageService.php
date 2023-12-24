<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\service;

use yii\db\ActiveRecord;
use kuaukutsu\poc\task\dto\StageModel;
use kuaukutsu\poc\task\dto\StageModelCreate;
use kuaukutsu\poc\task\dto\StageModelState;
use kuaukutsu\poc\task\service\StageCommand;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidCreate;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\shared\utils\ModelOperationSafely;

abstract class BaseStageService implements StageCommand
{
    use ModelOperationSafely;

    /**
     * @throws NotFoundException
     */
    abstract protected function getOne(PrimaryKeyInterface $pk): ActiveRecord;

    abstract protected function updateAll(array $attributes, array $condition, array $params = []): int;

    abstract protected function deleteAll(array $condition, array $params = []): int;

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    abstract protected function save(PrimaryKeyInterface $pk, array $attributes): StageModel;

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

    public function stateByTask(EntityUuid $uuid, StageModelState $model): bool
    {
        $flag = new TaskFlag();
        $rows = $this->updateAll(
            $model->toArray(),
            [
                'task_uuid' => $uuid->getUuid(),
                'flag' => [
                    $flag->unset()->setPaused()->toValue(),
                    $flag->unset()->setRunning()->toValue(),
                    $flag->unset()->setRunning()->setPaused()->toValue(),
                ],
            ]
        );

        return $rows > 0;
    }

    public function terminateByTask(array $indexUuid, StageModelState $model): bool
    {
        $flag = new TaskFlag();
        $rows = $this->updateAll(
            $model->toArray(),
            [
                'task_uuid' => $indexUuid,
                'flag' => $flag->unset()->setRunning()->toValue(),
            ]
        );

        return $rows > 0;
    }

    /**
     * @throws ModelDeleteException
     */
    public function removeByTask(EntityUuid $uuid): bool
    {
        $rows = $this->deleteAll(
            [
                'task_uuid' => $uuid->getUuid(),
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
}
