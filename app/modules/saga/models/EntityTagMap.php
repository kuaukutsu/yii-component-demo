<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\models;

use yii\db\ActiveRecord;
use kuaukutsu\poc\demo\shared\validator\UuidModelValidator;

/**
 * This is the model class for table "saga_entity_tag_map".
 *
 * @property string $entity_uuid
 * @property string $tag_uuid
 */
final class EntityTagMap extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%saga_entity_tag_map}}';
    }

    public function rules(): array
    {
        return [
            [['entity_uuid', 'tag_uuid'], 'required'],
            [['entity_uuid', 'tag_uuid'], UuidModelValidator::class],
        ];
    }
}
