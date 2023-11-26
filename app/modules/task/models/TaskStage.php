<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use kuaukutsu\ds\dto\Dtoable;
use kuaukutsu\poc\task\dto\StageDto;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;
use kuaukutsu\poc\demo\modules\task\components\BinaryBehavior;

/**
 * This is the model class for table "task_stage".
 *
 * @property string $uuid
 * @property string $task_uuid
 * @property string $title Название
 * @property int|null $flag Флаг состояния
 * @property string $state Сериализованное представление текущего состояния
 * @property string $handler Сериализованное представление обработчика
 * @property int|null $order Порядок в стеке
 * @property string $created_at
 * @property string $updated_at
 *
 * @method array<string, mixed> toArray(array $fields = [], array $expand = [], $recursive = true)
 */
final class TaskStage extends ActiveRecord implements Dtoable
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%task_stage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uuid', 'task_uuid', 'handler'], 'required'],
            [['uuid', 'task_uuid'], UuidModelValidator::class],
            [['flag', 'order'], 'integer'],
            [['state', 'handler'], 'safe'], // binary
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
                'attributes' => ['state', 'handler'],
            ],
        ];
    }

    public function toDto(): StageDto
    {
        return StageDto::hydrate($this->toArray());
    }
}
