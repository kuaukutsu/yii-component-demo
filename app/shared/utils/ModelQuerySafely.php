<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\utils;

use yii\db\ActiveRecord;
use yii\db\Exception;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;

trait ModelQuerySafely
{
    /**
     * @param non-empty-string $sqlCommand
     * @throws ModelSaveException
     */
    protected function querySafely(ActiveRecord $model, string $sqlCommand, array $params = []): array
    {
        $connection = $model::getDb();

        try {
            return $connection
                ->createCommand(
                    str_replace('__tableName__', $model::tableName(), $sqlCommand),
                    $params,
                )
                ->queryAll();
        } catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }
    }

    /**
     * @param non-empty-string $sqlCommand
     * @throws ModelSaveException
     */
    protected function scalarSafely(ActiveRecord $model, string $sqlCommand, array $params = []): int|string|false|null
    {
        $connection = $model::getDb();

        try {
            return $connection
                ->createCommand(
                    str_replace('__tableName__', $model::tableName(), $sqlCommand),
                    $params,
                )
                ->queryScalar();
        } catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }
    }
}
