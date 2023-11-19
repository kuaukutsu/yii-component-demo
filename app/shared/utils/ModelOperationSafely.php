<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\utils;

use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\ExpressionInterface;
use yii\helpers\Json;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;

/**
 * Позволяет перехватить допустимые yii\db\Exception и перевести их в ModelSaveException.
 * Иногда удобнее работать с одним типом исключения.
 * И хочется спрятать работу try catch опять же чтобы был корректный phpDoc.
 */
trait ModelOperationSafely
{
    /**
     * @throws ModelSaveException
     */
    protected function saveSafely(BaseActiveRecord $model): bool
    {
        try {
            $isSave = $model->save();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        if ($isSave === false) {
            throw new ModelSaveException($model);
        }

        return true;
    }

    /**
     * @throws ModelSaveException
     */
    protected function insertSafely(BaseActiveRecord $model): bool
    {
        try {
            $isSave = $model->insert();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        if ($isSave === false) {
            throw new ModelSaveException($model);
        }

        return true;
    }

    /**
     * @throws ModelSaveException
     */
    protected function upsertSafely(ActiveRecord $model, array|bool $updateColumns = false): bool
    {
        try {
            $rowCount = $model::find()
                ->createCommand()
                ->upsert(
                    $model::tableName(),
                    $model->toArray(),
                    $updateColumns
                )
                ->execute();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        return $rowCount > 0;
    }

    /**
     * @throws ModelSaveException
     */
    protected function batchSafely(ActiveRecord $model, array $columns, array $rows): bool
    {
        if ($rows === [] || $columns === []) {
            return false;
        }

        $connection = $model::getDb();

        $sql = $connection
            ->getQueryBuilder()
            ->batchInsert($model::tableName(), $columns, $rows);
        $sql .= ' ON CONFLICT DO NOTHING';

        try {
            $rowCount = $connection->createCommand($sql)->execute();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        return $rowCount > 0;
    }

    /**
     * @param string[] $updateNames
     * @throws ModelSaveException
     */
    protected function batchUpsertSafely(ActiveRecord $model, array $columns, array $rows, array $updateNames): bool
    {
        if ($rows === [] || $columns === []) {
            return false;
        }

        if ($updateNames === []) {
            return $this->batchSafely($model, $columns, $rows);
        }

        $connection = $model::getDb();

        /** @var array<string, scalar> $primaryKey */
        $primaryKey = (array)$model->getPrimaryKey();
        $uniqueColumns = implode(',', array_keys($primaryKey));

        $updateColumns = [];
        foreach ($updateNames as $name) {
            $updateColumns[$name] = $name . '=EXCLUDED.' . $connection->quoteColumnName($name);
        }

        $sql = $connection
            ->getQueryBuilder()
            ->batchInsert($model::tableName(), $columns, $rows);
        $sql .= " ON CONFLICT ($uniqueColumns) DO UPDATE SET " . implode(', ', $updateColumns);

        try {
            $rowCount = $connection->createCommand($sql)->execute();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        return $rowCount > 0;
    }

    /**
     * @param non-empty-string $sqlCommand
     * @throws ModelSaveException
     */
    protected function commandSafely(ActiveRecord $model, string $sqlCommand, array $params = []): bool
    {
        $connection = $model::getDb();

        try {
            $rowCount = $connection
                ->createCommand(
                    str_replace('__tableName__', $model::tableName(), $sqlCommand),
                    $params,
                )
                ->execute();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            /** @psalm-suppress RedundantCast */
            throw new ModelSaveException($model, $exception->getMessage(), (int)$exception->getCode());
        }

        return $rowCount > 0;
    }

    /**
     * @throws ModelDeleteException
     */
    protected function deleteSafely(BaseActiveRecord $model): bool
    {
        try {
            $isSave = $model->delete();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (Exception $exception) {
            throw new ModelDeleteException($exception->getMessage(), $exception);
        }

        if ($isSave === false) {
            throw new ModelDeleteException(
                sprintf('[%s] model is not delete.', $model::class)
            );
        }

        return true;
    }

    /**
     * @param array<array-key, mixed> $fields
     */
    protected function buildJsonbSet(Connection $connection, string $column, array $fields): ExpressionInterface
    {
        $pattern = "jsonb_set(%s, '{%s}', %s, true)";

        $fn = static fn(string|int|array $value): string => match (gettype($value)) {
            'string' => sprintf("to_jsonb(%s::text)", $connection->quoteValue($value)),
            'integer' => 'to_jsonb(' . $value . ')',
            default => '\'' . Json::encode($value) . '\''
        };

        $result = $column;

        /**
         * @var string $key
         * @var int|string|array $value
         */
        foreach ($fields as $key => $value) {
            $result = sprintf($pattern, $result, $key, $fn($value));
        }

        return new Expression($result);
    }

    /**
     * @param array<string, int> $counters
     */
    protected function buildCounters(array $counters): array
    {
        $n = 0;
        foreach ($counters as $name => $value) {
            $counters[$name] = new Expression("[[$name]]+:bp$n", [":bp$n" => $value]);
            $n++;
        }

        return $counters;
    }
}
