<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\service;

use yii\db\ActiveRecord;
use kuaukutsu\poc\task\dto\TaskModel;
use kuaukutsu\poc\task\dto\TaskModelCreate;
use kuaukutsu\poc\task\dto\TaskModelState;
use kuaukutsu\poc\task\service\TaskCommand;
use kuaukutsu\poc\task\state\TaskFlag;
use kuaukutsu\poc\task\EntityUuid;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidCreate;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\shared\utils\ModelOperationSafely;

abstract class BaseTaskService implements TaskCommand
{
    use ModelOperationSafely;

    /**
     * @throws NotFoundException
     */
    abstract protected function getOne(PrimaryKeyInterface $pk): ActiveRecord;

    abstract protected function updateAll(array $attributes, array $condition, array $params = []): int;

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    abstract protected function save(PrimaryKeyInterface $pk, array $attributes): TaskModel;

    /**
     * @throws ModelSaveException
     */
    public function create(EntityUuid $uuid, TaskModelCreate $model): TaskModel
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
    public function state(EntityUuid $uuid, TaskModelState $model): TaskModel
    {
        return $this->save(
            new PrimaryUuidUpdate($uuid->getUuid()),
            $model->toArray()
        );
    }

    public function terminate(array $indexUuid, TaskModelState $model): bool
    {
        $flag = new TaskFlag();
        $rows = $this->updateAll(
            $model->toArray(),
            [
                'uuid' => $indexUuid,
                'flag' => $flag->unset()->setRunning()->toValue(),
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
