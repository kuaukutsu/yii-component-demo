<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\migrations;

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
// @phpcs:ignore
final class m231125_080348_create_task extends Migration
{
    private string $table = '{{%task}}';

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
                'title' => $this->string(256)
                    ->notNull()
                    ->comment('Название'),
                'flag' => $this->smallInteger()
                    ->defaultValue(0)
                    ->comment('Флаг состояния'),
                'state' => $this->binary()
                    ->comment('Сериализованное представление текущего состояния'),
                'options' => $this->getDb()->getSchema()
                    ->createColumnSchemaBuilder('jsonb')
                    ->defaultValue('{}')
                    ->comment('TaskOptions'),
                'checksum' => $this->char(32)
                    ->notNull()
                    ->comment('checksum'),
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
            'I_' . $tableName . '_checksum',
            $this->table,
            [
                'checksum',
            ],
        );

        $this->createIndex(
            'I_' . $tableName . '_flag',
            $this->table,
            [
                'flag',
            ]
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
