<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * @property ActiveRecord $owner
 */
final class BinaryBehavior extends Behavior
{
    /**
     * @var string[] Attributes name
     */
    public array $attributes = [];

    public function events(): array
    {
        if ($this->attributes !== []) {
            return [
                BaseActiveRecord::EVENT_AFTER_FIND => 'prepareStreamContents',
            ];
        }

        return [];
    }

    public function prepareStreamContents(): void
    {
        foreach ($this->attributes as $attribute) {
            /** @var string|resource|null $value */
            $value = $this->owner->getAttribute($attribute);
            if (is_resource($value)) {
                $this->owner->setAttribute($attribute, stream_get_contents($value));
            }
        }
    }
}
