<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\service;

use kuaukutsu\poc\demo\shared\utils\ModelOperationSafely;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidCreate;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;
use kuaukutsu\poc\demo\modules\saga\models\SagaModel;
use kuaukutsu\poc\demo\modules\saga\models\Saga;

final class SagaService
{
    use ModelOperationSafely;

    /**
     * @param non-empty-string $uuid
     * @throws ModelSaveException
     */
    public function create(string $uuid, SagaModel $dto): SagaDto
    {
        return $this->save(
            new PrimaryUuidCreate($uuid),
            $dto->toArrayRecursive()
        );
    }

    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    public function update(string $uuid, SagaModel $dto): SagaDto
    {
        return $this->save(
            new PrimaryUuidUpdate($uuid),
            $dto->toArrayRecursive()
        );
    }

    /**
     * Use only in rollback.
     *
     * @throws NotFoundException
     * @throws ModelDeleteException
     */
    public function remove(string $key): void
    {
        $model = $this->getOne(new PrimaryUuidCreate($key));
        $this->deleteSafely($model);
    }

    /**
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    private function save(PrimaryKeyInterface $pk, array $attributes): SagaDto
    {
        $model = $pk->isNewRecord()
            ? new Saga($pk->getValue())
            : $this->getOne($pk);

        $model->setAttributes($attributes);
        $this->saveSafely($model);
        $model->refresh();

        return $model->toDto();
    }

    /**
     * @throws NotFoundException
     */
    private function getOne(PrimaryKeyInterface $pk): Saga
    {
        return Saga::findOne($pk->getValue())
            ?? throw new NotFoundException(
                strtr('[uuid] Saga not found.', $pk->getValue())
            );
    }
}
