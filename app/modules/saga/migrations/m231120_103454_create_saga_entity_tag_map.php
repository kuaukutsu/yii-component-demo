<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
// @phpcs:ignore
final class m231120_103454_create_saga_entity_tag_map extends Migration
{
    private string $table = '{{%saga_entity_tag_map}}';

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
                'entity_uuid' => $this->getDb()->getSchema()
                    ->createColumnSchemaBuilder('uuid')
                    ->notNull(),
                'tag_uuid' => $this->getDb()->getSchema()
                    ->createColumnSchemaBuilder('uuid')
                    ->notNull(),
            ]
        );

        $tableName = trim($this->getDb()->quoteSql($this->table), '"');

        $this->addPrimaryKey(
            'PK_' . $tableName . '_entity_tag_uuid',
            $this->table,
            [
                'entity_uuid',
                'tag_uuid',
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
