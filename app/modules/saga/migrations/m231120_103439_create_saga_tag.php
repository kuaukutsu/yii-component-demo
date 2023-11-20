<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
// @phpcs:ignore
final class m231120_103439_create_saga_tag extends Migration
{
    private string $table = '{{%saga_tag}}';

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
                'name' => $this->string(256)
                    ->notNull()
                    ->comment('Наименование'),
                'created_at' => $this->timestamp()
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
