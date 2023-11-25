<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use kuaukutsu\ds\dto\Dtoable;
use kuaukutsu\poc\task\dto\TaskDto;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;

/**
 * This is the model class for table "task".
 *
 * @property string $uuid
 * @property string $title Название
 * @property int|null $flag Флаг состояния
 * @property string $state Сериализованное представление текущего состояния
 * @property string $checksum checksum
 * @property string $created_at
 * @property string $updated_at
 *
 * @method array<string, mixed> toArray(array $fields = [], array $expand = [], $recursive = true)
 */
final class Task extends ActiveRecord implements Dtoable
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
            [['state'], 'string'],
        ];
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => gmdate("Y-m-d H:i:s"),
            ],
        ];
    }

    public function toDto(): TaskDto
    {
        return TaskDto::hydrate($this->toArray());
    }
}
