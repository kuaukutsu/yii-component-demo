<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
// @phpcs:ignore
final class m231125_080401_create_task_stage extends Migration
{
    private string $table = '{{%task_stage}}';

    public function init(): void
    {
        $this->db = 'db';

        parent::init();
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        $this->createTable(
            $this->table,
            [
                'uuid' => $this->getDb()->getSchema()
                    ->createColumnSchemaBuilder('uuid')
                    ->notNull(),
                'task_uuid' => $this->getDb()->getSchema()
                    ->createColumnSchemaBuilder('uuid')
                    ->notNull(),
                'flag' => $this->smallInteger()
                    ->defaultValue(0)
                    ->comment('Флаг состояния'),
                'state' => $this->text()
                    ->comment('Сериализованное представление текущего состояния'),
                'handler' => $this->text()
                    ->notNull()
                    ->comment('Сериализованное представление обработчика'),
                'order' => $this->smallInteger()
                    ->defaultValue(0)
                    ->comment('Порядок в стеке'),
                'created_at' => $this->timestamp()
                    ->notNull()
                    ->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()
                    ->notNull()
                    ->defaultExpression('CURRENT_TIMESTAMP'),
            ]
        );

        $tableName = trim($this->getDb()->quoteSql($this->table), '"');

        $this->addPrimaryKey(
            'PK_' . $tableName . '_uuid',
            $this->table,
            [
                'uuid',
            ]
        );

        $this->createIndex(
            'UI_' . $tableName . '_task_flag',
            $this->table,
            [
                'task_uuid',
                'flag',
                'order',
            ],
            true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(
            $this->table
        );
    }
}
