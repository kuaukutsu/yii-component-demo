<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
// @phpcs:ignore
final class m231119_135255_saga_test extends Migration
{
    private string $table = '{{%saga_test}}';

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
                'comment' => $this->string(1024)
                    ->notNull()
                    ->comment('Комментаий'),
                'flag' => $this->boolean()
                    ->defaultValue(false)
                    ->comment('true: commit, false: rollback'),
                'created_at' => $this->timestamp()
                    ->notNull()
                    ->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()
                    ->notNull()
                    ->defaultExpression('CURRENT_TIMESTAMP'),
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
