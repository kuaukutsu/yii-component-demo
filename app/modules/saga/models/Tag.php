<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use kuaukutsu\ds\dto\Dtoable;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;

/**
 * This is the model class for table "saga_tag".
 *
 * @property string $uuid
 * @property string $name
 * @property string $created_at
 *
 * @method array<string, mixed> toArray(array $fields = [], array $expand = [], $recursive = true)
 */
final class Tag extends ActiveRecord implements Dtoable
{
    public static function tableName(): string
    {
        return '{{%saga_tag}}';
    }

    public function rules(): array
    {
        return [
            [['uuid', 'name'], 'required'],
            [['uuid'], UuidModelValidator::class],
            [['name'], 'string', 'max' => 256],
        ];
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => gmdate("Y-m-d H:i:s"),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function toDto(): TagDto
    {
        return TagDto::hydrate($this->toArray());
    }
}
