<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use kuaukutsu\ds\dto\Dtoable;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;

/**
 * This is the model class for table "saga_entity".
 *
 * @property string $uuid
 * @property string $comment Комментаий
 * @property bool|null $flag true: commit, false: rollback
 * @property string $created_at
 * @property string $updated_at
 *
 * @method array<string, mixed> toArray(array $fields = [], array $expand = [], $recursive = true)
 */
final class Entity extends ActiveRecord implements Dtoable
{
    public static function tableName(): string
    {
        return '{{%saga_entity}}';
    }

    public function rules(): array
    {
        return [
            [['uuid', 'comment'], 'required'],
            [['uuid'], UuidModelValidator::class],
            [['comment'], 'string', 'max' => 1024],
            [['is_deleted'], 'boolean'],
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

    public function toDto(): EntityDto
    {
        return EntityDto::hydrate($this->toArray());
    }
}
