<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use kuaukutsu\poc\task\dto\TaskModel;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;
use kuaukutsu\poc\demo\modules\task\components\BinaryBehavior;

use function kuaukutsu\poc\task\tools\entity_hydrator;

/**
 * This is the model class for table "task".
 *
 * @property string $uuid
 * @property string $title Название
 * @property int|null $flag Флаг состояния
 * @property resource $state Сериализованное представление текущего состояния
 * @property array $options TaskOptions настройки
 * @property string $checksum checksum
 * @property string $created_at
 * @property string $updated_at
 *
 * @method array<string, int|string|array|null> toArray(array $fields = [], array $expand = [], $recursive = true)
 */
final class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uuid', 'title', 'checksum'], 'required'],
            [['uuid'], UuidModelValidator::class],
            [['flag'], 'integer'],
            [['title'], 'string', 'max' => 256],
            [['checksum'], 'string', 'max' => 32],
            [['state', 'options'], 'safe'], // binary
        ];
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => gmdate("Y-m-d H:i:s"),
            ],
            'binary' => [
                'class' => BinaryBehavior::class,
                'attributes' => ['state'],
            ],
        ];
    }

    public function toDto(): TaskModel
    {
        return entity_hydrator(TaskModel::class, $this->toArray());
    }
}
